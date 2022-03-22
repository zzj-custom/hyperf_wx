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
namespace App\Domain\Logic\Wx;

use App\Constants\Wx\MessageEnum;

class WxMessageLogic
{
    public function textMessageHandle(
        string $toUserName,
        string $msgType,
        string $content,
        string $openid
    ): string {
        return sprintf(
            MessageEnum::TEXT_TEMPLATE,
            $openid,  //接收方帐号（收到的OpenID）
            $toUserName,//开发者微信号
            time(),//消息创建时间 （整型）
            $msgType,//消息类型，文本为text
            '测试数据'//回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
        );
    }
}
