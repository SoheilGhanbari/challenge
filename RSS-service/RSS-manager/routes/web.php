<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['namespace' => 'Feed', 'prefix' => 'feeds'], function () use ($router)
{
   

    $router->get('/', 'FeedManagementController@index');
    $router->get('/{feed_id}', 'FeedManagementController@show');
    $router->post('/', 'FeedManagementController@store');
    $router->put('/{feed_id}', 'FeedManagementController@update');
    $router->patch('/{feed_id}', 'FeedManagementController@update');
    $router->delete('/{feed_id}', 'FeedManagementController@destroy');
    $router->group(['namespace' => 'Item', 'prefix' => '/{feed_id}/items'], function () use ($router)
    {
        $router->get('/', 'ItemManagementController@index');
        $router->get('/{item_id}', 'ItemManagementController@show');
        $router->put('/{item_id}', 'ItemManagementController@update');
        $router->patch('/{item_id}', 'ItemManagementController@update');
        $router->delete('/{item_id}', 'ItemManagementController@destroy');
    });
});

$router->group(['namespace' => 'Story', 'prefix' => 'stories'], function () use ($router)
{
    $router->get('/', 'StoryController@index');
});

