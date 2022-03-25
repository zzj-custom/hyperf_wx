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
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;

class YellowWordClient
{
    /**
     * @Inject
     */
    protected ClientFactory $clientFactory;

    /**
     * @throws RemoteException
     */
    public function handleYellowWord(): array
    {
        $client = $this->clientFactory->create();

        # 开启多协程
        $parallel = new Parallel(55);
        for ($i = 0; $i < 55; ++$i) {
            $parallel->add(function () use ($client, $i) {
                sleep($i * 1);

                //获取uri
                $uri = config('crawler.yellow_word.host');

                //获取数据
                $response = $client->get($uri, [
                    'query' => [
                        'export' => 'txt',  //txt, js, json
                    ],
                ])->getBody()->getContents();

                return [
                    'text' => $response,
                    'md5_txt' => md5($response),
                ];
            });
        }
        try {
            $results = $parallel->wait();
            LogUtil::get(__FUNCTION__)->info(json_encode($results, JSON_UNESCAPED_UNICODE));

            return $results;
        } catch (ParallelExecutionException $e) {
            LogUtil::get(__FUNCTION__)->info(__CLASS__, [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
            throw new RemoteException($e->getMessage(), $e->getCode());
        }
    }
}
