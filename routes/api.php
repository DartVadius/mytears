<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;


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
    ],
    function () {
        Route::get('/', 'Admin\AdminController@index');

        Route::get('/categories/deleted', 'Category\CategoryController@getDeletedCategory');
        Route::post('/categories', 'Category\CategoryController@createCategory');
        Route::put('/categories', 'Category\CategoryController@updateCategory');
        Route::put('/categories/{category_id}/restore', 'Category\CategoryController@restoreCategory')->middleware('integer');
        Route::delete('/categories/{category_id}', 'Category\CategoryController@deleteCategory')->middleware('integer');

        Route::post('/posts', 'Post\PostController@createPost');
        Route::put('/posts', 'Post\PostController@updatePost');
        // todo
        Route::get('/posts/deleted', 'Post\PostControlle@getDeletedPost');
        Route::put('/posts/{post_id}/restore', 'Post\PostController@restorePost')->middleware('integer');
        Route::delete('/posts/{post_id}', 'Post\PostController@deletePost')->middleware('integer');

        Route::post('/tags', 'Tag\TagController@createTag');
        Route::put('/tags', 'Tag\TagController@updateTag');
    }
);

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
        Route::get('/categories/{category_id?}', 'Category\CategoryController@getCategory')->middleware('integer');
        Route::get('/posts/{post_id?}', 'Post\PostController@getPost')->middleware('integer');
        Route::get('/tags/{tag_id?}', 'Tag\TagController@getTag')->middleware('integer');
    }
);