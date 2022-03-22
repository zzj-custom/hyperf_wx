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

class WxMessageLogic
{
    public function textMessageHandle(
        string $toUserName,
        string $msgType,
        string $content,
        string $openid
    ): array {
        return [
            'ToUserName' => $openid,   //接收方帐号（收到的OpenID）
            'FromUserName' => $toUserName, //开发者微信号
            'CreateTime' => time(),  //消息创建时间 （整型）
            'MsgType' => $msgType, //消息类型，文本为text
            'Content' => '测试数据', //回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
        ];
    }
}
