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

namespace App\Infrastructure\Service\Bing;

use App\Constants\ErrorCode;
use Contract\Exceptions\RemoteException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Arr;
use Psr\Http\Message\ResponseInterface;

class BingClient
{
    protected Client $client;

    private ClientFactory $clientFactory;

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
     * @throws RemoteException
     */
    public function request()
    {
        //获取uri
        $uri = vsprintf('%s/%s', [
            config('crawler.bing.host'),
            config('crawler.bing.api.crawler_image'),
        ]);

        //获取options
        $options = [
            'query' => [
                'format' => 'js', //非必填，默认返回json 返回数据格式，json和xml,
                'idx'    => 0,  // 非必填 请求图片截止天数, 0 今天, -1 截止中明天 （预准备的）1 截止至昨天，类推（目前最多获取到7天前的图片）
                'n'      => 1, // 1-8 返回请求数量，目前最多一次获取8张
                'mkt'    => 'zh-CN',  //非必填 地区
            ],
        ];

        try {
            //获取数据
            $response = $this->client->get($uri, $options);

            //处理数据
            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            throw new RemoteException($e->getMessage(), $e->getCode());
        }
    }

    public function handleResponse(ResponseInterface $response): array
    {
        //初始化返回参数
        $result = [];

        //获取参数
        $response = $response->getBody()->getContents();
        if (is_null($response)) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::BING_IMAGES_NOT_FUND),
                ErrorCode::BING_IMAGES_NOT_FUND
            );
        }

        //判断图片是否存在
        $response = json_decode($response ?? '', true);
        $response = Arr::get($response, 'images');
        if (! isset($response) || empty($response)) {
            throw new RemoteException(
                ErrorCode::getMessage(ErrorCode::BING_IMAGES_NOT_EXISTS),
                ErrorCode::BING_IMAGES_NOT_EXISTS
            );
        }

        $response = Arr::first($response);

        //获取图片
        $images_url = vsprintf('%s%s', [
            config('crawler.bing.host'),
            strstr(Arr::get($response, 'url'), '&', true),
        ]);

        //判断UHD图片是否存在
        $big_images_url = str_replace('1920x1080', 'UHD', $images_url);
        if (! @fopen($big_images_url, 'r')) {
            $big_images_url = $images_url;
        }

        //返回数据
        Arr::set($result, 'images_url', $big_images_url);
        Arr::set($result, 'date', date('Y-m-d', strtotime(Arr::get($response, 'startdate'))));
        Arr::set($result, 'name', Arr::get($response, 'copyright'));
        return $result;
    }
}
