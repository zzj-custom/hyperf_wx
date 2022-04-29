<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Domain\Logic\Word;

use App\Constants\ErrorCode;
use App\Constants\Word\WordEnum;
use App\Infrastructure\Service\Word\QiuShiBaiKeClient;
use App\Infrastructure\Service\Word\WordClient;
use App\Infrastructure\Service\Word\YellowWordClient;
use App\Infrastructure\Utils\LogUtil;
use App\Infrastructure\Utils\RedisUtil;
use App\Model\Ancient\AncientArticleModel;
use App\Model\Ancient\AncientAuthorModel;
use App\Model\Ancient\AncientCommentModel;
use App\Model\Ancient\AncientModel;
use App\Model\Word\BeautifulWordModel;
use App\Model\Word\YellowWordModel;
use Contract\Exceptions\RemoteException;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Codec\Json;

class WordLogic
{
    /**
     * @Inject
     */
    protected WordClient $wordClient;

    /**
     * @Inject
     */
    protected YellowWordClient $yellowWordClient;

    /**
     * @Inject
     */
    protected QiuShiBaiKeClient $qiuShiBaiKeClient;

    /**
     * 处理每日一词.
     *
     * @throws RemoteException
     */
    public function handleWordMessage(): bool
    {
        $response = $this->wordClient->handleBeautiful();
        LogUtil::get(__FUNCTION__)->info(__FUNCTION__, $response);
        return BeautifulWordModel::insert($response);
    }

    /**
     * 处理黄段子.
     *
     * @throws RemoteException
     */
    public function handleYellowWordMessage(): bool
    {
        $response = $this->yellowWordClient->handleYellowWord();
        if (! empty($response)) {
            //获取当前所有的MD5加密串
            $md5 = array_column($response, 'md5_txt');

            //查询数据
            $list = YellowWordModel::getOneDataByMd5($md5);

            //如果存在的话，那么就删除掉
            foreach ($response as $key => $value) {
                if (in_array(Arr::get($value, 'md5_txt'), $list)) {
                    unset($response[$key]);
                }
            }
        }
        LogUtil::get(__FUNCTION__)->info(__FUNCTION__, $response);
        return YellowWordModel::insert(array_values($response));
    }

    public function handleQiuShiBaiKe(string $type)
    {
        //获取数据
        $response = $this->qiuShiBaiKeClient->request($type);

        if (empty($response)) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::QIUSHIBAIKE_DATA_ERROR),
                ErrorCode::QIUSHIBAIKE_DATA_ERROR
            );
        }

        //初始化数据
        $insertData = [];

        foreach ($response as $value) {
            //获取内容
            $text   = Arr::get($value, 'content');
            $md5Txt = md5($text);

            //判断数据是否存在
            $yellowWordList = YellowWordModel::filterByMd5Txt($md5Txt)->first();
            if (is_null($yellowWordList)) {
                //添加数据
                $insertData = Arr::prepend($insertData, [
                    'text'    => $text,
                    'md5_txt' => $md5Txt,
                ]);
            }
        }

        //插入数据
        if (! empty($insertData)) {
            var_dump(count($insertData));
            YellowWordModel::insert($insertData);
        }

//        else {
//            //如果没有那么后面的也是重复数据
//            RedisUtil::set(WordEnum::QIUSHIBAIKE_REDIS_KEY . ":{$type}", 1);
//        }
        return $insertData;
    }

    public function initAncient()
    {
        $ancientPath = BASE_PATH . '/public/ancient';
        if (! is_dir($ancientPath)) {
            throw new RemoteException('ancient目录不存在', ErrorCode::SERVER_ERROR);
        }

        $files = [];
        $this->searchDir($ancientPath, $files);

        if (empty($files)) {
            throw new RemoteException('文件为空', ErrorCode::SERVER_ERROR);
        }

        foreach ($files as $value) {
            Db::transaction(function () use ($value) {
                $this->handleFileContent($value);
            });
        }
    }

    /**
     * 获取当前目录下面的所有文件
     *
     * @param $path
     * @param $files
     */
    public function searchDir($path, &$files)
    {
        if (is_dir($path)) {
            $opendir = opendir($path);

            while ($file = readdir($opendir)) {
                if ($file != '.' && $file != '..') {
                    $this->searchDir($path . '/' . $file, $files);
                }
            }
            closedir($opendir);
        }
        if (! is_dir($path)) {
            $files[] = $path;
        }
    }

    /**
     * 处理作者数据
     *
     * @param  string          $fileName
     * @throws RemoteException
     */
    public function handleFileContent(string $fileName)
    {
        $content = Json::decode(file_get_contents($fileName));
        $content = array_pop($content);
        if (empty($content)) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::E_NOT_FOUND_RECORD),
                ErrorCode::E_NOT_FOUND_RECORD
            );
        }
        foreach ($content as $item) {
            //作者相关数据
            $authorName    = Arr::get($item, 'book.tb_author.nameStr');
            $authorDesc    = Arr::get($item, 'book.tb_author.cont');
            $authorDynasty = Arr::get($item, 'book.tb_author.chaodai');
            if (! empty($authorName)) {
                $authorId      = $this->getAuthorId($authorName, $authorDesc, $authorDynasty);
            } else {
                $authorId = 0;
            }

            //文章分类数据
            $categoryName   = Arr::get($item, 'book.tb_book.nameStr');
            $categoryDesc   = Arr::get($item, 'book.tb_book.cont');

            //分类数据
            [$ancientId, $ancientTypeId] = $this->getAncientAndTypeId(Arr::get($item, 'book.tb_book.type'));

            //文章数据
            $this->handleArticleData(
                Arr::get($item, 'book.tb_bookviews.bookviews'),
                $ancientTypeId,
                $ancientId,
                $categoryName,
                $categoryDesc,
                $authorId
            );
        }
    }

    /**
     * 添加文章和评论
     *
     * @param  array           $article       文章数据
     * @param  string          $ancientTypeId 古文类型id
     * @param  string          $ancientId     古文id
     * @param  string          $categoryName  文章分类名称
     * @param  string          $categoryDesc  文章分类描述
     * @param  int             $authorId      作者id
     * @throws RemoteException
     */
    private function handleArticleData(
        array $article,
        string $ancientTypeId,
        string $ancientId,
        string $categoryName,
        string $categoryDesc,
        int $authorId
    ): void {
        if (empty($article)) {
            LogUtil::get(__FUNCTION__)->notice($authorId);
            throw new RemoteException("{$authorId}的文章未发现", ErrorCode::E_NOT_FOUND_RECORD);
        }

        foreach ($article as $value) {
            $name     = Arr::get($value, 'content.tb_bookview.nameStr') ?? '';
            $content  = Arr::get($value, 'content.tb_bookview.cont');
            $videoUrl = Arr::get($value, 'content.tb_bookview.langduUrl');

            $articleList = AncientArticleModel::filterByAncientArticle($name, $categoryName)->first();
            LogUtil::get(__FUNCTION__)->info($name);
            var_dump($name, $categoryName, is_null($articleList));
            if (is_null($articleList)) {
                //创建文章
                $articleId = AncientArticleModel::insertGetId([
                    'ancient_id'      => $ancientId,
                    'ancient_type_id' => $ancientTypeId,
                    'name'            => $name,
                    'content'         => $content,
                    'category_name'   => $categoryName,
                    'category_desc'   => $categoryDesc,
                    'author_id'       => $authorId,
                    'video_url'       => $videoUrl,
                ]);

                //获取评论数据
                $commentData   = Arr::get($value, 'content.tb_fanyis.bvfanyis');
                $commentInsert = [];
                if (is_array($commentData) && ! empty($commentData)) {
                    foreach ($commentData as $item) {
                        $commentInsert = Arr::prepend($commentInsert, [
                            'name'       => Arr::get($item, 'nameStr'),
                            'author'     => Arr::get($item, 'author'),
                            'desc'       => Arr::get($item, 'cont'),
                            'article_id' => $articleId,
                            'url'        => Arr::get($item, 'cankao'),
                        ]);
                    }
                }
                AncientCommentModel::insert($commentInsert);
            }
        }
    }

    /**
     * 获取古文分类数据
     *
     * @param string $ancientTypeName
     * @return array
     */
    private function getAncientAndTypeId(string $ancientTypeName): array
    {
        $ancientList = AncientModel::filterByAncient($ancientTypeName)->first();
        if (is_null($ancientList)) {
            $ancientId     = '';
            $ancientTypeId = '';
        } else {
            $ancientId     = $ancientList->ancient_id;
            $ancientTypeId = $ancientList->ancient_type_id;
        }
        return [$ancientId, $ancientTypeId];
    }

    private function getAuthorId(string $name, string $desc, string $dynasty): int
    {
        //获取作者名称
        $authorData = AncientAuthorModel::filterByAncientAuthor($name)->first();
        if (is_null($authorData)) {
            $authorId = AncientAuthorModel::insertGetId([
                'name'    => $name,
                'desc'    => $desc,
                'dynasty' => $dynasty,
            ]);
        } else {
            $authorId = $authorData->id;
        }
        return $authorId;
    }
}
