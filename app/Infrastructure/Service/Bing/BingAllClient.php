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
namespace App\Infrastructure\Service\Bing;

use App\Infrastructure\Utils\LogUtil;
use App\Model\Bing\BingImagesModel;
use Contract\Exceptions\RemoteException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Arr;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

class BingAllClient
{
    protected ClientFactory $clientFactory;

    /**
     * @var
     */
    protected Client $client;

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

    /**
     * @param mixed $page
     * @throws RemoteException
     */
    public function request($page): array
    {
        //设置options
        $options = [
            'query' => [
                'p' => $page,
            ],
        ];

        try {
            $response = $this->client->get(
                config('crawler.bing.all_host'),
                empty($page) ? [] : $options
            );

            //记录日志
            LogUtil::get(__CLASS__)->info(__CLASS__, [$response]);
        } catch (GuzzleException $exception) {
            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }

        $response = $this->handleResponse($response);

        if (! empty($response)) {
            echo $page;
            BingImagesModel::insert($response);
        }
        return $response;
    }

    /**
     * @throws RemoteException
     */
    public function handleResponse(ResponseInterface $response): array
    {
        //获取请求主体
        $response = $response->getBody()->getContents();

        //初始化返回数据
        $result = [];

        //处理xpath
        $crawler = new Crawler();
        $crawler->addHtmlContent($response);
        try {
            //这里使用的是xpath语法，对进行内容获取
            $crawler->filterXPath('//*[@class="item"]')->each(function (Crawler $node, $i) use (&$result) {
                //获取图片地址
                $image_url = $node->filterXPath('//div[contains(@class,"progressive")]/img')->attr('data-progressive');

                //将图片后缀去掉
                $image_url = strstr($image_url, '?', true);

                //将图片获取改为1920*1080
                $big_image_url = str_replace('640x480', '1920x1080', $image_url);

                //判断转化的图片地址是否存在
                if (! @fopen($big_image_url, 'r')) {
                    $big_image_url = $image_url;
                }

                $item = [
                    'images_url' => $big_image_url,
                    'name' => $node->filterXPath('//div[contains(@class,"progressive")]/div[1]/h3')->text(),
                    'date' => $node->filterXPath('//div[contains(@class,"progressive")]/div[1]/p[1]/em')->text(),
                ];

                $result = Arr::prepend($result, $item);
            });
        } catch (Exception $exception) {
            throw new RemoteException($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }
}
