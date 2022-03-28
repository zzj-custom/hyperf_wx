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
use App\Model\Word\BeautifulWordModel;
use App\Model\Word\YellowWordModel;
use Contract\Exceptions\RemoteException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;

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
            $text = Arr::get($value, 'content');
            $md5Txt = md5($text);

            //判断数据是否存在
            $yellowWordList = YellowWordModel::filterByMd5Txt($md5Txt)->first();
            if (is_null($yellowWordList)) {
                //添加数据
                $insertData = Arr::prepend($insertData, [
                    'text' => $text,
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
}
