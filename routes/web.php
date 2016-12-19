<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => ['web','auth','admin']], function() {

    Route::get('/members/new','MemberController@create');

    Route::post('/members','MemberController@create');
    Route::post('/members/{member}/adults','Member\SetAdults');
    Route::post('/members/{member}/children','Member\SetChildren');
    Route::post('/members/{member}/phones','Member\SetPhones');
    Route::post('/members/{member}/emails','Member\SetEmails');

    Route::post('/members/{member}/delete','Member\Delete');

});

Route::group(['middleware' => ['web','auth']], function() {
    Route::get('/', 'MemberController@index');
    Route::get('/members', 'MemberController@index');
    Route::get('/test', 'MemberController@test');
    Route::get('/members/{member}','MemberController@display');
    Route::get('/members/{member}/guests','MemberController@guests');

    Route::post('/members/{member}/records','MemberRecordController@create');
});

Route::group(['middleware' => 'web'], function() {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');

});


