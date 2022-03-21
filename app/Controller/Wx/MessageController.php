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
namespace App\Controller\Wx;

use App\Controller\AbstractController;
use App\Infrastructure\Utils\LogUtil;
use App\Request\Wx\Message\GetUserMessageRequest;

class MessageController extends AbstractController
{
    public function getUserMessage(GetUserMessageRequest $getUserMessageRequest)
    {
        $params = $getUserMessageRequest->validated();
        LogUtil::get(__FUNCTION__)->info('params', $this->request->all());
    }
}
