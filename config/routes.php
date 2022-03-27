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

require 'routes/message.php';
require 'routes/file.php';

// 添加微信公众号的验证
Router::addRoute(['GET', 'POST'], '/index/verifyToken', 'App\Controller\IndexController@verifyToken');
Router::addRoute(['GET', 'POST'], '/index/responseMsg', 'App\Controller\IndexController@responseMsg');

Router::get('/favicon.ico', function () {
    return '';
});
