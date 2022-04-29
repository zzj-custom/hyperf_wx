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

namespace App\Controller;

use App\Domain\Logic\Bing\BingLogic;
use App\Domain\Logic\token\TokenLogic;
use App\Domain\Logic\Word\WordLogic;
use App\Domain\Logic\Wx\WxMessageLogic;
use App\Domain\Service\BingService;
use App\Infrastructure\Service\Baidu\BaiduTranslateClient;
use App\Infrastructure\Service\Bing\BingAllClient;
use App\Infrastructure\Service\Qiniu\QiNiuFileUpload;
use App\Infrastructure\Utils\LogUtil;
use App\Request\Index\VerifyTokenRequest;
use App\Request\Wx\Message\CheckSignatureRequest;
use App\Task\Word\QiuShiBaiKeTask;
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
     */
    protected BingAllClient $bingAllClient;

    /**
     * @Inject
     */
    protected QiNiuFileUpload $qiniu;

    /**
     * @Inject
     */
    protected BingLogic $bingLogic;

    /**
     * @Inject
     */
    protected QiuShiBaiKeTask $qiuShiBaiKeTask;

    /**
     * @Inject
     */
    protected BaiduTranslateClient $baiduTranslateClient;

    public function index()
    {
        $user   = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function verifyToken(VerifyTokenRequest $verifyTokenRequest)
    {
        //获取请求参数
        $params       = $verifyTokenRequest->validated();
        $toUserName   = Arr::get($params, 'ToUserName');
        $fromUserName = Arr::get($params, 'FromUserName');
        $createTime   = Arr::get($params, 'CreateTime');
        $msgType      = Arr::get($params, 'MsgType');
        $content      = Arr::get($params, 'Content');
        $msgId        = Arr::get($params, 'MsgId');
        $openid       = Arr::get($params, 'openid');

        $method = "{$msgType}MessageHandle";
        return $this->wxMessageLogic->{$method}($toUserName, $msgType, $content, $openid);
    }

    public function checkSignature(CheckSignatureRequest $checkSignatureRequest): string
    {
        LogUtil::get(__FUNCTION__)->notice('params', [
            'signature' => $checkSignatureRequest->all()['signature'],
            'timestamp' => $checkSignatureRequest->all()['timestamp'],
            'nonce'     => $checkSignatureRequest->all()['nonce'],
            'echostr'   => $checkSignatureRequest->all()['echostr'],
        ]);
        $params = $checkSignatureRequest->validated();
        return $this->tokenLogic->verifyToken($params);
    }

    public function responseMsg()
    {
        $this->wordLogic->initAncient();
        //$this->wordLogic->handleFileContent('/Users/zouzhujia/Applications/github/hyperf_wx/public/ancient/子部/小说家类5.json');
    }
}
