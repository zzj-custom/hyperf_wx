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
use App\Controller\Wx\MessageController;
use Hyperf\HttpServer\Router\Router;

Router::addGroup('/message', function () {
    Router::post('/getUserMessage', [MessageController::class, 'getUserMessage']);
    Router::post('/test', [MessageController::class, 'test']);
});
