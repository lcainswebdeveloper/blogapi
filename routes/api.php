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

Route::post('/user/login', 'LoginController@login');

Route::get('/posts/categories', 'CategoryController@index');
Route::get('/post/categories/{param}', 'CategoryController@show');
Route::middleware('auth:api')->post('/category/create', 'CategoryController@store');
Route::middleware('auth:api')->post('/category/{id}/update', 'CategoryController@update');

Route::get('/posts/blog-posts', 'BlogPostController@index');
Route::get('/post/blog-posts/{param}', 'BlogPostController@show');
Route::middleware('auth:api')->post('/blog-post/create', 'BlogPostController@store');
Route::middleware('auth:api')->post('/blog-post/{id}/update', 'BlogPostController@update');
