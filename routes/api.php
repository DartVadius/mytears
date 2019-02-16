<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

/**
 * public resources
 */
Route::group(
    [
        'namespace' => 'Api',
    ],
    function () {
        Route::get('/', 'HomeController@index');
        Route::post('/register', 'Auth\RegisterController@register');
        Route::get('/categories/{category_id?}', 'Category\CategoryController@getCategory');
        Route::get('/posts/{post_id?}', 'Post\PostController@getPost');
    }
);

/**
 * authorized resources
 */
Route::group(
    [
        'middleware' => ['auth:api'],
        'namespace' => 'Api'
    ],
    function () {
        Route::get('/user', function (Request $request) {
            return new UserResource($request->user());
        });
    }
);

/**
 * admin resources
 */
Route::group(
    [
        'middleware' => ['auth:api', 'can:admin'],
        'namespace' => 'Api',
//        'prefix' => 'admin'
    ],
    function () {
        Route::get('/', 'Admin\AdminController@index');
        Route::post('/categories', 'Category\CategoryController@createCategory');
        Route::put('/categories', 'Category\CategoryController@updateCategory');
        Route::post('/posts', 'Post\PostController@createPost');
        Route::put('/posts', 'Post\PostController@updatePost');
    }
);