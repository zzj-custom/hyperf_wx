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
use Hyperf\HttpServer\Router\Router;

// 添加微信公众号的验证
Router::get('/index/verifyToken', 'App\Controller\IndexController@verifyToken');

Router::get('/favicon.ico', function () {
    return '';
});
