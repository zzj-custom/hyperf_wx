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

use App\Constants\ErrorCode;
use Psr\Http\Message\ResponseInterface;

class BaseController extends AbstractController
{
    /**
     * @param $data
     */
    public function success($data, string $message = null, int $code = null): ResponseInterface
    {
        return $this->response->json([
            'msg'    => $message ?? ErrorCode::getMessage(ErrorCode::SERVER_SUCCESS),
            'code'   => $code ?? ErrorCode::SERVER_SUCCESS,
            'result' => $data,
        ]);
    }

    public function error(string $message = null, int $code = null): ResponseInterface
    {
        return $this->response->json([
            'msg'    => $message ?? ErrorCode::getMessage(ErrorCode::SERVER_ERROR),
            'code'   => $code ?? ErrorCode::SERVER_ERROR,
            'result' => [],
        ]);
    }
}
