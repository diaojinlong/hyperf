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

use App\Middleware\Home\AuthMiddleware as HomeAuthMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\Home\IndexController@index');
Router::addRoute(['POST'], '/user/login', 'App\Controller\Home\UserController@login');
Router::addRoute(['POST'], '/user/register', 'App\Controller\Home\UserController@register');
Router::get('/user/info', 'App\Controller\Home\UserController@info', ['middleware' => [HomeAuthMiddleware::class]]);
Router::post('/user/refresh_token', 'App\Controller\Home\UserController@refreshToken');

Router::get('/favicon.ico', function () {
    return '';
});
