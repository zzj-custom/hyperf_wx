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

namespace App\Infrastructure\Service\Baidu;

use App\Infrastructure\Utils\LogUtil;
use Contract\Exceptions\RemoteException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

class BaiduTranslateClient
{
    protected ClientFactory $clientFactory;

    /**
     * @var Client
     */
    protected Client $client;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;

        // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
        $options = [
            'User-Agent' => config('crawler.user_agent'),
            'Accept'     => 'application/json',
            'timeout'    => 60,
        ];

        // $client 为协程化的 GuzzleHttp\Client 对象
        $this->client = $this->clientFactory->create($options);
    }

    /**
     * @param  string          $query 需要翻译的文本
     * @param  string          $from  需要翻译文本的语言
     * @param  string          $to    翻译结果的语言
     * @throws RemoteException
     */
    public function request(string $query, string $from = 'zh', string $to = 'en'): string
    {
        $requestId = uniqid();

        //获取url, app_id, secret_key
        $url       = config('crawler.baidu_translate.url');
        $appId     = config('crawler.baidu_translate.app_id');
        $secretKey = config('crawler.baidu_translate.secret_key');

        //设置options
        $options = [
            'query' => [
                'q'     => $query,
                'appid' => $appId,
                'salt'  => $requestId,
                'from'  => $from,
                'to'    => $to,
                'sign'  => $this->getSign($query, $appId, $requestId, $secretKey),
            ],
        ];

        //记录请求参数
        LogUtil::get(__CLASS__)->notice($requestId, [
            'options' => $options,
            'url'     => $url,
        ]);

        try {
            $response = $this->client->post(
                $url,
                $options
            );

            //记录日志
            LogUtil::get(__CLASS__)->info($requestId, [
                'response' => $response,
                'options'  => $options,
                'url'      => $url,
            ]);
        } catch (GuzzleException $exception) {
            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }

        return $this->handleResponse($response);
    }

    /**
     * @throws RemoteException
     */
    public function handleResponse(ResponseInterface $response): string
    {
        try {
            //获取请求主体
            $response = $response->getBody()->getContents();
            $response = Json::decode($response);

            //判断错误
            if (isset($response['error_code'])) {
                throw new RemoteException(
                    Arr::get($response, 'error_msg'),
                    Arr::get($response, 'error_code')
                );
            }

            if (! isset($response['trans_result'])) {
                throw new RemoteException('获取结果失败', 500);
            }

            $transResult = Arr::get($response, 'trans_result');
            $transResult = array_pop($transResult);
            return Arr::get($transResult, 'dst');
        } catch (Exception $exception) {
            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }
    }

    private function getSign(string $query, string $appId, string $salt, string $secKey): string
    {
        $sign = $appId . $query . $salt . $secKey;
        return md5($sign);
    }
}
