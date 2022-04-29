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

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;

    /**
     * @Message("success")
     */
    public const SERVER_SUCCESS = 200;

    public const VALIDATION_ERROR = 422;

    /** @Message("NOT FOUND") */
    public const E_NOT_FOUND_RECORD = 404;

    /**
     * @Message("必应图片获取失败")
     */
    public const BING_IMAGES_NOT_FUND = 400100;

    /**
     * @Message("必应图片不存在")
     */
    public const BING_IMAGES_NOT_EXISTS = 400101;

    /*  文件  */
    /**
     * @Message("文件不存在")
     */
    public const FILE_NOT_EXISTS = 400200;

    /*  糗事百科  */
    /**
     * @Message("糗事百科获取数据失败")
     */
    public const QIUSHIBAIKE_DATA_NOT_FOUND = 400300;

    /**
     * @Message("糗事百科接口返回错误")
     */
    public const QIUSHIBAIKE_DATA_ERROR = 400301;
}
