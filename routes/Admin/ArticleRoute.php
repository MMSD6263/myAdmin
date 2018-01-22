<?php
/**
 * Created by PhpStorm.
 * User: zxf
 * Date: 2016/8/23
 * Time: 11:35
 */

$router->group(['prefix' => 'article'], function ($router) {
    $router->get('index', 'ArticleController@index');
    $router->get('list', 'ArticleController@lists');
    $router->any('addArticle', 'ArticleController@addArticle');
    $router->any('trans', 'ArticleController@trans');
    $router->any('preview', 'ArticleController@preview');
    $router->any('webUploader', 'ArticleController@webUploader');
    $router->get('articleEdit', 'ArticleController@articleEdit');   //修改页面
    $router->any('articleDelete', 'ArticleController@articleDelete');  //删除文章


    $router->any('ajaxData', 'ArticleController@ajaxData');
    $router->any('editArticle', 'ArticleController@editArticle');
    $router->any('editPreview', 'ArticleController@editPreview');
    $router->any('addVideo', 'ArticleController@addVideo');
    $router->any('makeLink', 'ArticleController@makeLink');
    $router->any('catchOne', 'ArticleController@catchOne');
    $router->any('materialList', 'ArticleController@materialList');
    $router->any('pointSee','ArticleController@PointSee');
    $router->any('category','ArticleController@CateGory');
    $router->any('cateajaxData','ArticleController@CateAjaxData');
    $router->any('updateData','ArticleController@UpdateData');
    $router->any('saveData','ArticleController@SaveData');
    $router->any('issue','ArticleController@Issue');
    $router->any('Cancel','ArticleController@Cancel');

    $router->any('getImg','ArticleController@getImg');
    $router->any('type_column','TypeController@index');
    $router->any('getArticleDetail','ArticleController@getArticleDetail');
    $router->any('fetchArticle','ArticleController@fetchArticle');
    $router->post('delSmallPic','ArticleController@delSmallPic');


    $router->any('test','ArticleController@test');

});
