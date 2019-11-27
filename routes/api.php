<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('cors')->group(function () {
    // Check Api Source
    Route::get('/main', 'Api\OfficialSiteController@index');

    Route::get('/article', 'Article\ArticleController@articleapi');

    Route::get('/article/{id}', 'Article\ArticleController@articledetailapi');

    Route::get('/articlecate', 'Article\ArticleController@articlecateapi');
});
