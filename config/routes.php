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

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/roc', 'App\Controller\IndexController@rpc');

Router::get('/favicon.ico', function () {
    return '';
});


Router::post('/transIn', [\App\Controller\IndexController::class,'transIn']);
Router::post('/transOut', [\App\Controller\IndexController::class,'transOut']);
Router::post('/excpt', [\App\Controller\IndexController::class,'excpt']);


Router::addServer('grpc', function () {
    Router::addGroup('/grpc.hi', function () {
        Router::post('/transIn', 'App\Controller\HiController@transIn');
        Router::post('/transOut', 'App\Controller\HiController@transOut');
    });
});