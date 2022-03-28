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
namespace App\Infrastructure\Service\Word;

use App\Constants\ErrorCode;
use App\Constants\Word\WordEnum;
use App\Infrastructure\Utils\LogUtil;
use App\Infrastructure\Utils\RedisUtil;
use Contract\Exceptions\RemoteException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Arr;
use Psr\Http\Message\ResponseInterface;

class QiuShiBaiKeClient
{
    private ClientFactory $clientFactory;

    private Client $client;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;

        // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
        $options = [
            'User-Agent' => config('crawler.user_agent'),
            'Accept' => 'application/json',
            'timeout' => 60,
        ];

        // $client 为协程化的 GuzzleHttp\Client 对象
        $this->client = $this->clientFactory->create($options);
    }

    public function request(string $type = 'latest'): array
    {
        try {
            //初始化页数
            if (! RedisUtil::exists(WordEnum::QIUSHIBAIKE_REDIS_KEY . ":{$type}")) {
                RedisUtil::set(WordEnum::QIUSHIBAIKE_REDIS_KEY . ":{$type}", 1);
            }

            //获取uri
            $uri = vsprintf('%s%s', [
                config('crawler.qiushibaike.host'),
                config('crawler.qiushibaike.api.article'),
            ]);

            //获取options
            $options = [
                'query' => [
                    'type' => 'text', //参数type为类型，latest最新、text文本、image图片、video视频
                    'page' => RedisUtil::get(WordEnum::QIUSHIBAIKE_REDIS_KEY . ":{$type}"), //参数page为页码
                    'count' => 12, //参数count为每页数量
                ],
            ];

            //获取数据
            $response = $this->client->get($uri, $options);

            return $this->handleResponse($response, $type);
        } catch (GuzzleException $exception) {
            //添加日志
            LogUtil::get(__CLASS__)->info(__CLASS__, [
                'message' => $exception->getMessage(),
            ]);

            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @throws RemoteException
     */
    public function handleResponse(ResponseInterface $response, string $type): array
    {
        //获取content
        $response = $response->getBody()->getContents();
        if (empty($response)) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::QIUSHIBAIKE_DATA_NOT_FOUND),
                ErrorCode::QIUSHIBAIKE_DATA_NOT_FOUND
            );
        }

        $response = json_decode($response, true);

        //判断请求code
        $err = Arr::get($response, 'err');
        if (! isset($err) && $err != 0) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::QIUSHIBAIKE_DATA_ERROR),
                ErrorCode::QIUSHIBAIKE_DATA_ERROR
            );
        }

        //redis增加
        RedisUtil::incr(WordEnum::QIUSHIBAIKE_REDIS_KEY . ":{$type}");

        return Arr::get($response['items']);
    }
}
