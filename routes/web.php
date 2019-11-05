<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

DB::listen(function($query){
    Log::info($query->sql);
});

Route::match(['get', 'post'], '/login', 'Rbac\UserController@login');

Route::get('/logout', 'Rbac\UserController@logout');

Route::group(['middleware' => ['islogin']], function () {

    Route::get('/', 'Index\IndexController@Index');

    Route::get('/role', 'Rbac\RoleController@Index');

    Route::get('/permission', 'Rbac\PermissionController@Index');

    Route::match(['get', 'post'], '/user', 'Rbac\UserController@Index');

    Route::match(['get', 'post'], '/user/add', 'Rbac\UserController@add');

    Route::match(['get', 'post'], '/user/edit', 'Rbac\UserController@edit');

    Route::match(['get', 'post'], '/mysetting', 'Rbac\UserController@mysetting');

    Route::post('/user/del', 'Rbac\UserController@delete');

    Route::get('/user/detail', 'Rbac\UserController@detail');

});
