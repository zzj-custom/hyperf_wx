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
namespace App\Infrastructure\Service\CompanyWxApiClient\Request;

use Hyperf\Utils\Arr;

trait MessageRequest
{
    public function sendUserMessage(string $content)
    {
        //获取token
        $accessToken = $this->getAccessToken();

        //构建请求方法
        $method = vsprintf('/cgi-bin/message/send?access_token=%s', [$accessToken]);

        $response = $this->request($method, [
            'touser' => config('api.company_wx.to_user'),
            'msgtype' => 'text',
            'agentid' => config('api.company_wx.agentid'),
            'text' => [
                'content' => $content,
            ],
            'safe' => 0,
        ], 'json');

        return Arr::get($response, 'msgid');
    }
}
