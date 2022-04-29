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

namespace App\Infrastructure\Service\CompanyWxApiClient\Request;

use App\Infrastructure\Utils\RedisUtil;
use Hyperf\Utils\Arr;

trait TokenRequest
{
    public function getAccessToken(): string
    {
        $key = config('api.company_wx.corpid');

        //判断redis是否存在 ，如果不存在那么获取api数据
        $accessToken = RedisUtil::get($key);

        if (empty($accessToken)) {
            $response = $this->request('/cgi-bin/gettoken', [
                'corpid'     => $key,
                'corpsecret' => config('api.company_wx.corpsecret'),
            ]);
            $accessToken = Arr::get($response, 'access_token');

            //设置redis
            RedisUtil::set($key, $accessToken, Arr::get($response, 'expires_in') - 600);
        }

        return $accessToken;
    }
}
