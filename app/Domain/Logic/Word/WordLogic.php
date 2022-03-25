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

use App\Infrastructure\Service\Word\WordClient;
use App\Infrastructure\Service\Word\YellowWordClient;
use App\Infrastructure\Utils\LogUtil;
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
}
