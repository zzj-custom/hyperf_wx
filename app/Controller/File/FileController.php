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

namespace App\Controller\File;

use App\Controller\BaseController;
use App\Domain\Logic\File\FileLogic;
use App\Request\File\UploadFileRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;

class FileController extends BaseController
{
    /**
     * @Inject
     */
    protected FileLogic $fileLogic;

    public function uploadFile(UploadFileRequest $uploadFileRequest)
    {
        //验证参数
        $params = $uploadFileRequest->validated();

        //获取上传的文件
        $upload = Arr::get($params, 'upload');

        $response = $this->fileLogic->uploadFile($upload);

        return $this->success($response);
    }
}
