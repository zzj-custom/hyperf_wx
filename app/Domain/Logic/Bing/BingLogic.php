<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Domain\Logic\Bing;

use App\Infrastructure\Service\Bing\BingAllClient;
use App\Infrastructure\Service\Bing\BingClient;
use App\Infrastructure\Service\Qiniu\QiNiuFileUpload;
use App\Infrastructure\Utils\LogUtil;
use App\Model\Bing\BingImagesModel;
use Contract\Exceptions\RemoteException;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;

class BingLogic
{
    /**
     * @Inject
     */
    protected BingAllClient $bingAllClient;

    /**
     * @Inject
     */
    protected BingClient $bingClient;

    /**
     * @Inject
     */
    protected QiNiuFileUpload $qiNiuFileUpload;

    public function getBingImages()
    {
        $result = [];
        for ($i = 184; $i < 185; ++$i) {
            sleep(5);
            $result = Arr::merge($result, $this->bingAllClient->request($i));
        }
        LogUtil::get(__FUNCTION__)->info(__FUNCTION__, [
            'result' => $result,
        ]);
        return true;
    }

    /**
     * @throws RemoteException
     */
    public function getBingImagesByDay()
    {
        try {
            //获取必应图片
            $imagesData = $this->bingClient->request();
            $images_url = Arr::get($imagesData, 'images_url');

            //判断图片是否存在数据库中
            $bingImagesList = BingImagesModel::filterByBingImages(
                Arr::get($imagesData, 'name')
            )->first();
            if (is_null($bingImagesList)) {
                //创建数据
                BingImagesModel::insertGetId($imagesData);

                //上传七牛云
                $this->qiNiuFileUpload->fileUpload($images_url);
            } else {
                LogUtil::get(__FUNCTION__)->info(date('Y-m-d') . '今日必应文件以获取');
            }
        } catch (Exception $exception) {
            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }
    }
}
