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
     * @Message("必应图片获取失败")
     */
    public const BING_IMAGES_NOT_FUND = 400100;

    /**
     * @Message("必应图片不存在")
     */
    public const BING_IMAGES_NOT_EXISTS = 400101;
}
