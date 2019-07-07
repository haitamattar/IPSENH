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

Route::get('/', function () {
    return view('welcome',  [
        'loggedIn' => 'false',
        'token' => ''
    ]);
});

Route::namespace('Auth')->group(function () {
    Route::get('/auth/register/{provider}', 'RegisterController@redirectToExternalSignIn');
    Route::get('/auth/callback/{provider}', 'RegisterController@handleExternalSignInCallback');
});

// TODO: user namespace??
Route::get('/auth/addToExisting/{user}/{provider}', 'UserController@getExternalAuthRedirect');
Route::get('/auth/callback/{provider}/addToExisting', 'UserController@addGithubConnectionToUserCallback');


Route::get('/project/{any}', function () {
    return view('welcome',  [
        'loggedIn' => 'false',
        'token' => ''
    ]);
});

Route::get('/profilePage/{any}', function () {
    return view('welcome',  [
        'loggedIn' => 'false',
        'token' => ''
    ]);
});

Route::get('/{any}', function () {
    return view('welcome',  [
        'loggedIn' => 'false',
        'token' => ''
    ]);
});
