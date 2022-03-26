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

use App\Domain\Logic\Bing\BingLogic;
use App\Domain\Logic\token\TokenLogic;
use App\Domain\Logic\Word\WordLogic;
use App\Domain\Logic\Wx\WxMessageLogic;
use App\Domain\Service\BingService;
use App\Infrastructure\Service\Bing\BingAllClient;
use App\Infrastructure\Service\Qiniu\QiNiuFileUpload;
use App\Infrastructure\Utils\LogUtil;
use App\Request\Index\VerifyTokenRequest;
use App\Request\Wx\Message\CheckSignatureRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
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

    /**
     * @Inject
     */
    protected WordLogic $wordLogic;

    /**
     * @Inject
     */
    protected BingService $bingService;

    /**
     * @Inject
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @Inject
     * @var BingAllClient
     */
    protected BingAllClient $bingAllClient;

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
        return $this->wxMessageLogic->{$method}($toUserName, $msgType, $content, $openid);
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

    /**
     * @Inject()
     * @var QiNiuFileUpload
     */
    protected QiNiuFileUpload $qiniu;

    /**
     * @Inject()
     * @var BingLogic
     */
    protected BingLogic $bingLogic;

    public function responseMsg()
    {
        return $this->bingLogic->getBingImagesByDay();
        return $this->bingAllClient->request();
        return $this->wordLogic->handleYellowWordMessage();
        $data = $this->clientFactory->create()->get('https://m2.qiushibaike.com/article/list/text?type=refresh&page=&count=12')->getBody()->getContents();
        return json_decode($data, true);
        return $this->bingService->schedule('getBingImagesByDay');
        return $this->wordLogic->handleWordMessage();
    }
}
