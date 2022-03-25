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
namespace App\Constants\CompanyWx;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("企业微信接口获取失败")
     */
    public const COMPANY_WX_API_ERROR = 400000;

    /**
     * @Message("企业微信接口数据获取失败")
     */
    public const COMPANY_WX_API_RESULT_ERROR = 400001;
}
