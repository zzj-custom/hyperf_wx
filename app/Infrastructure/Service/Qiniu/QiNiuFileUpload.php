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
namespace App\Infrastructure\Service\Qiniu;

use App\Infrastructure\Utils\LogUtil;
use Contract\Exceptions\RemoteException;
use Hyperf\Filesystem\FilesystemFactory;
use League\Flysystem\FilesystemException;

class QiNiuFileUpload
{
    protected FilesystemFactory $filesystemFactory;

    protected FilesystemFactory $factoty;

    public function __construct(FilesystemFactory $filesystemFactory)
    {
        $this->factory = $filesystemFactory->get('qiniu');
    }

    /**
     * @param string $file
     * @return bool
     * @throws RemoteException
     */
    public function fileUpload(string $file): bool
    {
        $pattern = '/(https?|ftp|file):\/\/[-A-Za-z0-9+&@#\/%?=~_|!:,.;]+[-A-Za-z0-9+&@#\/%=~_|]/';

        //获取basename
        $basename = pathinfo($file, PATHINFO_BASENAME);

        if (preg_match($pattern, $file)) {
            //远程图片
        }

        //获取location
        $location = "bing/{$basename}";

        try {
            //判断文件是否存在
            $exists = $this->factory->fileExists($location);

            //上传文件
            if (! $exists) {
                $this->factory->write($location, file_get_contents($file));
            }
        } catch (FilesystemException $exception) {
            //记录日志
            LogUtil::get(__FUNCTION__)->info(__FUNCTION__, [
                'message' => $exception->getMessage(),
            ]);
            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }
        return true;
    }
}
