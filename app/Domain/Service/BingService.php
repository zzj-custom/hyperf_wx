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

namespace App\Domain\Service;

use App\Infrastructure\Utils\LogUtil;
use Contract\Exceptions\RemoteException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;

class BingService
{
    /**
     * @Inject
     */
    protected ClientFactory $clientFactory;

    public function schedule(string $method): void
    {
        $this->{$method}();
    }

    /**
     * @throws RemoteException
     */
    public function getBingImagesByDay(): bool
    {
        $client = $this->clientFactory->create();

        # 开启多协程
        $parallel = new Parallel(8);
        for ($i = 0; $i < 8; ++$i) {
            $parallel->add(function () use ($client, $i) {
                sleep($i * 4);
                // 拉取数据
                $response = $client->get(sprintf(
                    '%s%s?format=js&idx=%d&n=%d',
                    config('crawler.bing.host'),
                    config('crawler.bing.api.crawler_image'),
                    $i,
                    $i + 1
                ))->getBody()->getContents();
                $response = json_decode($response, true);

                // 创建文件夹
                if (! is_dir(BASE_PATH . '/public')) {
                    mkdir(BASE_PATH . '/public', 0777, true);
                }

                // 获取图片
                foreach ($response['images'] as $value) {
                    # 获取文件名称
                    $extension = pathinfo(explode('&', parse_url($value['url'])['query'])[0])['extension'];
                    file_put_contents(
                        BASE_PATH . '/public/' . sprintf('%s.%s', $value['hsh'], $extension),
                        file_get_contents(
                            sprintf('%s%s', trim(config('crawler.bing.host'), '/'), $value['url'])
                        )
                    );
                }
                return implode('#', array_column($response['images'], 'hsh'));
            });
        }
        try {
            $results = $parallel->wait();
            LogUtil::get(__FUNCTION__)->info(json_encode($results, JSON_UNESCAPED_UNICODE));
            return true;
        } catch (ParallelExecutionException $e) {
            throw new RemoteException(sprintf(
                '%s:%s',
                json_encode($e->getResults(), JSON_UNESCAPED_UNICODE),  // 获取协程中的返回值。
                $e->getThrowables()    // 获取协程中出现的异常。
            ));
        }
    }
}
