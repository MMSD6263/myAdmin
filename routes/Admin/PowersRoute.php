<?php
/**
 * Created by PhpStorm.
 * User: zxf
 * Date: 2016/8/23
 * Time: 11:35
 */

$router->group(['prefix' => 'powers'], function ($router) {
    $router->get('index', 'PowerController@index');
    $router->any('ajaxData', 'PowerController@ajaxData');
    $router->post('add', 'PowerController@add');
    $router->any('edit', 'PowerController@edit');
    $router->post('removes', 'PowerController@removes');
    $router->any('powertree', 'PowerController@powertree');


});