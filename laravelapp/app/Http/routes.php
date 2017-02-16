<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

/*
Route::group(['middleware' => 'auth', 'namespace' => 'Admin', 'prefix' => 'admin'], function() {  
    Route::get('/', 'HomeController@index');
    Route::get('user', 'UserController@index');
});
*/


Route::group(['middleware' => 'web'], function () {
    //Route::auth();

    //前台用户
    Route::any('home/login', 'Auth\AuthController@login');
    Route::get('home/logout', 'Auth\AuthController@logout');
    Route::any('home/register', 'Auth\AuthController@register');

    
    //前台用户 微信登录
    Route::any('home/oauthlogin', 'Auth\AuthController@oauthlogin');
    Route::any('home/oauthregister', 'Auth\AuthController@oauthregister');


    Route::get('/home', 'HomeController@index');
    Route::get('/home/profile', 'Home\ProfileController@index');
    Route::any('/home/coupon', 'Home\UserController@coupon');
    Route::get('/test', 'Home\ProfileController@test');

    Route::any('/home/user', 'Home\UserController@index');
    Route::any('/home/user_callback', 'Home\UserController@user_callback');
    
    Route::any('/home/myshare', 'Home\ShareController@myshare');
    Route::any('/home/myshare_callback', 'Home\ShareController@myshare_callback');

    Route::any('/home/share/{id}', 'Home\ShareController@share');

    Route::any('/home/share_callback/{id}', 'Home\ShareController@share_callback');

    Route::get('/hello/{name}',function($name){
    return "Hello {$name}!";
    });

	Route::any('/getauth', 'WechatController@getauth');
	Route::any('/auth_callback', 'WechatController@auth_callback');

	Route::any('/getqrcode', 'WechatController@getqrcode');
	Route::any('/setmenu', 'WechatController@setmenu');

	Route::any('/getshucai', 'WechatController@getshucai');
	Route::any('/upload', 'WechatController@upload');
	Route::any('/setcode', 'WechatController@setcode');

    //后台管理员
    Route::any('admin/login', 'Admin\AuthController@login');
    Route::any('admin/logout', 'Admin\AuthController@logout');
    Route::any('admin/register', 'Admin\AuthController@register');

    Route::get('/admin', 'AdminController@index');
});

    Route::any('/wechat', 'WechatController@serve');

/*
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
*/