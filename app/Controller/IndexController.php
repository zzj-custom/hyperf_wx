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
use App\Infrastructure\Utils\LogUtil;
use App\Request\Index\VerifyTokenRequest;
use Hyperf\Di\Annotation\Inject;

class IndexController extends AbstractController
{
    /**
     * @Inject
     * @var TokenLogic
     */
    protected $tokenLogic;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    /**
     * @param VerifyTokenRequest $verifyTokenRequest
     * @return string
     */
    public function verifyToken(VerifyTokenRequest $verifyTokenRequest): string
    {
        LogUtil::get(__FUNCTION__)->notice('params', [
            'signature' => $verifyTokenRequest->all()['signature'],
            'timestamp' => $verifyTokenRequest->all()['timestamp'],
            'nonce' => $verifyTokenRequest->all()['nonce'],
            'echostr' => $verifyTokenRequest->all()['echostr'],
        ]);
        $params = $verifyTokenRequest->validated();
        return $this->tokenLogic->verifyToken($params);
    }
}
