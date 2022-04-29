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

namespace App\Domain\Logic\token;

use Hyperf\Utils\Arr;

class TokenLogic
{
    /**
     * 验证微信token.
     */
    public function verifyToken(array $params): string
    {
        //获取参数
        $token     = config('open.wx.token');
        $timestamp = Arr::get($params, 'timestamp');
        $nonce     = Arr::get($params, 'nonce');
        $signature = Arr::get($params, 'signature');
        $echoStr   = Arr::get($params, 'echostr');

        //获取验证数据，并排序
        $list = [$token, $timestamp, $nonce];
        sort($list, SORT_STRING);
        $verifySign = sha1(implode($list));

        //验证sign
        if ($verifySign !== $signature) {
            return '';
        }
        return $echoStr;
    }
}
