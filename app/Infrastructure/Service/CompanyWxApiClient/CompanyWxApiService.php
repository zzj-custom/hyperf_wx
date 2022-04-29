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

namespace App\Infrastructure\Service\CompanyWxApiClient;

use App\Constants\CompanyWx\ErrorCode;
use App\Infrastructure\Service\CompanyWxApiClient\Request\MessageRequest;
use App\Infrastructure\Service\CompanyWxApiClient\Request\TokenRequest;
use App\Infrastructure\Utils\LogUtil;
use Contract\Exceptions\RemoteException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Arr;
use Psr\Http\Message\ResponseInterface;

class CompanyWxApiService
{
    use TokenRequest;
    use MessageRequest;

    public Client $client;

    private ClientFactory $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;

        // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
        $options = [
            'base_uri' => config('api.company_wx.base_uri'),
        ];

        // $client 为协程化的 GuzzleHttp\Client 对象
        $this->client = $this->clientFactory->create($options);
    }

    /**
     * @throws RemoteException
     */
    public function request(string $uri, ?array $params = null, string $method = 'query'): array
    {
        $requestID = uniqid();

        try {
            $options = [
                $method => $params,
            ];
            // TODO: 接入数据库日志
            LogUtil::get('company_wx_api')->info($requestID, [$uri, $options]);
            $response = $this->client->post($uri, $options);
        } catch (GuzzleException $e) {
            LogUtil::get('company_wx_api')->error($requestID, [$e->getMessage(), $e->getTraceAsString()]);
            throw new RemoteException(ErrorCode::getMessage(ErrorCode::COMPANY_WX_API_ERROR), ErrorCode::COMPANY_WX_API_ERROR);
        }

        return $this->handleResponse($response, $requestID);
    }

    /**
     * @throws RemoteException
     */
    protected function handleResponse(ResponseInterface $response, string $requestID): array
    {
        $responseContent = json_decode($response->getBody()->getContents(), true);
        LogUtil::get('company_wx_api')->info($requestID, $responseContent);

        $errorCode = Arr::get($responseContent, 'errcode');
        if ($errorCode !== 0) {
            throw new RemoteException(Arr::get($responseContent, 'errmsg'), ErrorCode::COMPANY_WX_API_RESULT_ERROR);
        }

        return $responseContent;
    }
}
