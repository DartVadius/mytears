<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

Route::get('/', 'Api\HomeController@index');
Route::get('/categories/{category_id?}', 'Api\Category\CategoryController@getCategory');
Route::post('/register', 'Api\Auth\RegisterController@register');

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

Route::group(
    [
        'middleware' => ['auth:api', 'can:admin'],
        'namespace' => 'Api',
        'prefix' => 'admin'
    ],
    function () {
        Route::get('/', 'Admin\AdminController@index');
        Route::post('/categories', 'Category\CategoryController@createCategory');
        Route::put('/categories', 'Category\CategoryController@updateCategory');
    }
);