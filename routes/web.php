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

    Route::get('/users/json','UserController@getUserData');
    Route::get('/users','UserController@getIndex');

    Route::post('/users/{user}/flags','UserController@setFlags');
    Route::post('/users/{user}/delete','UserController@delete');
    Route::post('/users/{user}/password','Auth\PasswordController@setOtherPass');

    Route::get('/users/new','UserController@showNewForm');
    Route::post('/users','UserController@create');

    Route::get('/reports','ReportController@reportView');
    Route::get('/reports/guests','ReportController@guestReport');
    Route::get('/reports/members','ReportController@memberReport');
    Route::get('/receipts/{record}','ReportController@guestReceipt');
    Route::get('/reports/directory','ReportController@memberDirectory');

});

Route::group(['middleware' => ['web','auth', 'temp']], function() {
    Route::get('/', 'MemberController@index');
    Route::get('/members', 'MemberController@index');
    Route::get('/members/json', 'MemberController@memberData');
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

    Route::get('images/{filename}', function ($filename)
    {
        $path = storage_path() . '\\app\\signatures\\' . $filename;

        if(!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });

//    Route::get('test', function () {
//        $record = \App\GuestRecord::with('guests', 'member')->find(1);
//        return view('reports.guestReceipt', compact('record'));
//    });
});


