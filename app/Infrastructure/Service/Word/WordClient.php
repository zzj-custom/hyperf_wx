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

use App\Infrastructure\Utils\LogUtil;
use Contract\Exceptions\RemoteException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;

class WordClient
{
    /**
     * @Inject
     */
    protected ClientFactory $clientFactory;

    /**
     * @throws RemoteException
     */
    public function handleBeautiful(): array
    {
        $client = $this->clientFactory->create();

        # 开启多协程
        $parallel = new Parallel(55);
        for ($i = 0; $i < 55; ++$i) {
            $parallel->add(function () use ($client, $i) {
                sleep($i * 5);

                //随机取某个类型
                $str = str_shuffle('abcdefg');
                $key = rand(0, 8);
                $type = substr($str, $key, 1);

                // 获取uri
                $uri = config('crawler.word.host');
                if (! empty($type)) {
                    $uri = vsprintf('%s?c=%s', [
                        $uri, $type,
                    ]);
                }

                //获取数据
                return $client->get($uri)->getBody()->getContents();
            });
        }
        try {
            $results = $parallel->wait();
            LogUtil::get(__FUNCTION__)->info(json_encode($results, JSON_UNESCAPED_UNICODE));

            return $this->handleResponse($results);
        } catch (ParallelExecutionException $e) {
            LogUtil::get(__FUNCTION__)->info(__CLASS__, [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            throw new RemoteException($e->getMessage(), $e->getCode());
        }
    }

    public function handleResponse(array $contents): array
    {
        $response = [];

        if (! empty($contents)) {
            foreach ($contents as $value) {
                $value = json_decode($value, true);
                $response = Arr::prepend($response, [
                    'hitokoto' => Arr::get($value, 'hitokoto'),
                    'type' => Arr::get($value, 'type'),
                    'from' => Arr::get($value, 'from'),
                    'from_who' => Arr::get($value, 'from_who'),
                    'commit_from' => Arr::get($value, 'commit_from'),
                ]);
            }
        }

        return $response;
    }
}
