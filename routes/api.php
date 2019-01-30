<?php

use Illuminate\Http\Request;

Route::get('/', 'Api\HomeController@index');
Route::post('/register', 'Api\Auth\RegisterController@register');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'middleware' => ['auth:api', 'can:admin']
    ],
    function () {
        Route::get('/admin', 'Api\Admin\AdminController@index')->name('admin');
    }
);