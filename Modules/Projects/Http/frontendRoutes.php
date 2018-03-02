<?php

use Illuminate\Routing\Router;

/** @var Router $router */
$router->group(['prefix' => 'projects'], function (Router $router) {
    $locale = LaravelLocalization::setLocale() ? : App::getLocale();
    $router->get('/', [
        'as' => $locale . '.projects',
        'uses' => 'PublicController@index',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
    $router->get('{slug}', [
        'as' => $locale . '.projects.slug',
        'uses' => 'PublicController@show',
        'middleware' => config('asgard.blog.config.middleware'),
    ]);
});
