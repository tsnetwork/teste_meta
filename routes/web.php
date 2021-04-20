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

$router->get('/' ,[
    "as" => 'home', 
    "uses" => 'MainController@index'
]);

$router->group(['prefix' => 'abelha'], function () use ($router) {
    $router->post('/nova', 'MainController@novaAbelha');
    $router->delete('/{id}/deletar', 'MainController@deletarAbelha');

});

$router->group(['prefix' => 'planta'], function () use ($router) {
    $router->post('/nova', 'MainController@novaPlanta');
    $router->put('/{id}/editar', 'MainController@editarPlanta');
    $router->delete('/{id}/deletar', 'MainController@deletarPlanta');
});
