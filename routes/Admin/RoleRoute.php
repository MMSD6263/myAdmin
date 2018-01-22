<?php
/**
 * Created by PhpStorm.
 * User: zxf
 * Date: 2016/8/23
 * Time: 11:35
 */

$router->group(['prefix' => 'role'], function($router){
    $router->get('index','RoleController@index');
    $router->any('subadd','RoleController@subadd');
    $router->any('subedit','RoleController@subedit');
    $router->any('ajaxdata','RoleController@ajaxdata');
    $router->any('roleAdd','RoleController@roleAdd');
    $router->any('remove','RoleController@remove');
});