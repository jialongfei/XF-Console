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

// Record SQL
DB::listen(function($query){
    Log::info($query->sql);
});



// Auth
Route::match(['get', 'post'], '/login', 'Rbac\UserController@login');
Route::get('/logout', 'Rbac\UserController@logout');

// Check login status
Route::group(['middleware' => ['islogin','checkper']], function () {

    // Main page
    Route::get('/', 'Index\IndexController@Index');

    // User
    Route::match(['get', 'post'], '/user', 'Rbac\UserController@index');
    Route::match(['get', 'post'], '/user/add', 'Rbac\UserController@add');
    Route::match(['get', 'post'], '/user/edit', 'Rbac\UserController@edit');
    Route::match(['get', 'post'], '/user/role', 'Rbac\UserController@changerole');
    Route::post('/user/del', 'Rbac\UserController@delete');
    Route::get('/user/detail', 'Rbac\UserController@detail');

    // My setting
    Route::match(['get', 'post'], '/mysetting', 'Rbac\UserController@mysetting');
    Route::match(['get', 'post'], '/resetpwd', 'Rbac\UserController@resetpwd');
    Route::post('/upload/avatar', 'Rbac\UserController@uploadAvatar');

    // Role
    Route::match(['get', 'post'], '/role', 'Rbac\RoleController@index');
    Route::match(['get', 'post'], '/role/add', 'Rbac\RoleController@add');
    Route::match(['get', 'post'], '/role/edit', 'Rbac\RoleController@edit');
    Route::match(['get', 'post'], '/role/permission', 'Rbac\RoleController@changeper');
    Route::post('/role/del', 'Rbac\RoleController@delete');
    Route::get('/role/detail', 'Rbac\RoleController@detail');

    // Permission
    Route::match(['get', 'post'], '/permission', 'Rbac\PermissionController@index');
    Route::match(['get', 'post'], '/permission/add', 'Rbac\PermissionController@add');
    Route::match(['get', 'post'], '/permission/edit', 'Rbac\PermissionController@edit');
    Route::post('/permission/del', 'Rbac\PermissionController@delete');
    Route::get('/permission/detail', 'Rbac\PermissionController@detail');

    // Newscate
    Route::match(['get', 'post'], '/newscate', 'Rbac\PermissionController@index');

    // News
    Route::match(['get', 'post'], '/news', 'Rbac\PermissionController@index');

});
