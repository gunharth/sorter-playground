<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/projects'], function (Router $router) {
    $router->bind('project', function ($id) {
        return app('Modules\Projects\Repositories\ProjectRepository')->find($id);
    });
    $router->get('projects', [
        'as' => 'admin.projects.project.index',
        'uses' => 'ProjectController@index',
        'middleware' => 'can:projects.projects.index'
    ]);
    $router->get('projects/create', [
        'as' => 'admin.projects.project.create',
        'uses' => 'ProjectController@create',
        'middleware' => 'can:projects.projects.create'
    ]);
    $router->post('projects', [
        'as' => 'admin.projects.project.store',
        'uses' => 'ProjectController@store',
        'middleware' => 'can:projects.projects.create'
    ]);
    $router->get('projects/{project}/edit', [
        'as' => 'admin.projects.project.edit',
        'uses' => 'ProjectController@edit',
        'middleware' => 'can:projects.projects.edit'
    ]);
    $router->put('projects/{project}', [
        'as' => 'admin.projects.project.update',
        'uses' => 'ProjectController@update',
        'middleware' => 'can:projects.projects.edit'
    ]);
    $router->delete('projects/{project}', [
        'as' => 'admin.projects.project.destroy',
        'uses' => 'ProjectController@destroy',
        'middleware' => 'can:projects.projects.destroy'
    ]);
// append

});
