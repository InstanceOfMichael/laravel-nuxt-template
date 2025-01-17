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

Route::prefix('api')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'Auth\LoginController@logout');

        Route::get('/user', function (Request $request) {
            return $request->user()->makeVisible('email', 'email_verified_at');
        });

        Route::patch('settings/email', 'Settings\EmailController@update');
        Route::patch('settings/profile', 'Settings\ProfileController@update');
        Route::patch('settings/password', 'Settings\PasswordController@update');
    });

    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'Auth\LoginController@login');
        Route::post('register', 'Auth\RegisterController@register');

        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset');

        Route::post('oauth/{provider}', 'Auth\OAuthController@redirectToProvider');
        Route::get('oauth/{provider}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
    });

    Route::group([], function () {
        Route::resource('comments', 'CommentController');

        Route::resource('users', 'UserController');
        Route::group([ 'prefix' => 'users/{user}' ], function () {
            Route::resource('comments', 'User\CommentController', [ 'only' => 'index' ]);
        });
    });

    Route::group(['middleware' => 'auth:api'], function () {
        // Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
        Route::post('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
    });
});
