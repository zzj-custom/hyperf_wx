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
namespace App\Controller;

use App\Domain\Logic\token\TokenLogic;
use App\Domain\Logic\Wx\WxMessageLogic;
use App\Infrastructure\Utils\LogUtil;
use App\Request\Index\VerifyTokenRequest;
use App\Request\Wx\Message\CheckSignatureRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Arr;

class IndexController extends AbstractController
{
    /**
     * @Inject
     */
    protected TokenLogic $tokenLogic;

    /**
     * @Inject
     */
    protected WxMessageLogic $wxMessageLogic;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function verifyToken(VerifyTokenRequest $verifyTokenRequest)
    {
        //获取请求参数
        $params = $verifyTokenRequest->validated();
        $toUserName = Arr::get($params, 'ToUserName');
        $fromUserName = Arr::get($params, 'FromUserName');
        $createTime = Arr::get($params, 'CreateTime');
        $msgType = Arr::get($params, 'MsgType');
        $content = Arr::get($params, 'Content');
        $msgId = Arr::get($params, 'MsgId');
        $openid = Arr::get($params, 'openid');

        $method = "{$msgType}MessageHandle";
        $response = $this->wxMessageLogic->{$method}($toUserName, $msgType, $content, $openid);
        return $this->response->xml($response);
    }

    public function checkSignature(CheckSignatureRequest $checkSignatureRequest): string
    {
        LogUtil::get(__FUNCTION__)->notice('params', [
            'signature' => $checkSignatureRequest->all()['signature'],
            'timestamp' => $checkSignatureRequest->all()['timestamp'],
            'nonce' => $checkSignatureRequest->all()['nonce'],
            'echostr' => $checkSignatureRequest->all()['echostr'],
        ]);
        $params = $checkSignatureRequest->validated();
        return $this->tokenLogic->verifyToken($params);
    }

    public function responseMsg()
    {
        var_dump($this->request->all());
    }
}
