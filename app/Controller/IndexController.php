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

use App\Infrastructure\Utils\LogUtil;
use App\Request\Index\VerifyTokenRequest;

class IndexController extends AbstractController
{
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
        LogUtil::get(__FUNCTION__)->notice('params', [
            'signature' => $verifyTokenRequest->all()['signature'],
            'timestamp' => $verifyTokenRequest->all()['timestamp'],
            'nonce' => $verifyTokenRequest->all()['nonce'],
            'echostr' => $verifyTokenRequest->all()['echostr'],
        ]);
        $params = $verifyTokenRequest->validated();
        LogUtil::get(__FUNCTION__)->info(json_encode($params, JSON_UNESCAPED_UNICODE));
    }

    public function yar(){
        new Yar_Client();
    }
}
