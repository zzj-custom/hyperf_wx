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
namespace App\Domain\Logic\File;

use App\Constants\ErrorCode;
use App\Model\Word\TrainModel;
use Contract\Exceptions\RemoteException;

class FileLogic
{
    public function uploadFile($upload)
    {
        var_dump($this->getBigFileContent($upload->getRealPath()));
        return [];
        $stream = fopen($upload->getRealPath(), 'r+');
    }

    public function getBigFileContent($filePath)
    {
        //判断文件是否存在
        if (! file_exists($filePath)) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::FILE_NOT_EXISTS),
                ErrorCode::FILE_NOT_EXISTS
            );
        }

        //获取文件内容
        $fp = fopen($filePath, 'r+');

        //分块的大小
        $chunk = 4096;

        //逐行读取文件内容
        if ($fp) {
            //判断行数
            $num = 1;
            while (($info = fgets($fp, $chunk)) !== false) {
                $info = trim($info);
                //存入数据库
                TrainModel::where('id', '=', $num)->update([
                    'out_train' => $info,
                ]);
                ++$num;
            }
        }
        fclose($fp);
    }
}
