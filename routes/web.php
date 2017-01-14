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

Route::group(['middleware' => ['web','auth', 'temp', 'admin']], function() {

    Route::get('/members/new','MemberController@create');

    Route::post('/members','MemberController@create');
    Route::post('/members/{member}/adults','Member\SetAdults');
    Route::post('/members/{member}/children','Member\SetChildren');
    Route::post('/members/{member}/phones','Member\SetPhones');
    Route::post('/members/{member}/emails','Member\SetEmails');
    Route::post('/members/{member}/address','Member\SetAddress');

    Route::post('/members/{member}/delete','Member\Delete');

    Route::get('/users/new','UserController@showNewForm');
    Route::post('/users','UserController@create');


});

Route::group(['middleware' => ['web','auth', 'temp']], function() {
    Route::get('/', 'MemberController@index');
    Route::get('/members', 'MemberController@index');
    Route::get('/test', 'MemberController@test');
    Route::get('/members/{member}','MemberController@display');
    Route::get('/members/{member}/json','MemberController@individualData');

    Route::get('/members/{member}/guests','GuestController@index');
    Route::get('/members/{member}/guests/json','GuestController@json');
    Route::get('/members/{member}/guests/new','GuestController@check_in');

    Route::post('/members/{member}/guests/new','GuestRecordController@create');
    Route::post('/members/{member}/guests/{record}/delete','GuestRecordController@delete');

    Route::post('/members/{member}/records','MemberRecordController@create');

    Route::post('members/{member}/balance','Member\BalanceController@charge');
    Route::get('members/{member}/balance','Member\BalanceController@get');
    Route::get('members/{member}/balance/json','Member\BalanceController@json');
    Route::get('members/{member}/balance/quick','Member\BalanceController@lastFive');


    Route::get('members/{member}/notes/json','Member\NoteController@json');
    Route::get('members/{member}/notes/quick','Member\NoteController@lastFive');
    Route::get('members/{member}/notes','Member\NoteController@get');

    Route::post('members/{member}/notes','Member\NoteController@create');
    Route::post('members/{member}/notes/{note}/delete','Member\NoteController@delete');

    Route::get('/change-pass','Auth\PasswordController@showChangeForm');
    Route::post('/change-pass','Auth\PasswordController@changePassword');

    Route::get('/set-pass','Auth\PasswordController@showTempForm');
    Route::post('/set-pass','Auth\PasswordController@setOwnPass');

});

Route::group(['middleware' => 'web'], function() {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout');

});


