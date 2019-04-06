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

    Route::resource('answers', 'AnswerController');
    Route::group([ 'prefix' => 'answers/{answer}' ], function () {
        Route::resource('comments', 'Answer\CommentController');
    });

    Route::resource('claims', 'ClaimController');
    Route::group([ 'prefix' => 'claims/{claim}' ], function () {
        Route::resource('claimsides', 'Claim\ClaimsideController');
        Route::resource('claimtopics', 'Claim\ClaimtopicController');
        Route::resource('comments', 'Claim\CommentController');
    });

    Route::resource('claimsides', 'ClaimsideController');
    Route::group([ 'prefix' => 'claimsides/{claimside}' ], function () {
        Route::resource('comments', 'Claimside\CommentController');
    });

    Route::resource('claimrelations', 'ClaimrelationController');
    Route::group([ 'prefix' => 'claimrelations/{claimrelation}' ], function () {
        Route::resource('comments', 'Claimrelation\CommentController');
    });

    Route::resource('questions', 'QuestionController');
    Route::group([ 'prefix' => 'questions/{question}' ], function () {
        Route::resource('allowedquestionsides', 'Question\AllowedquestionsideController');
        Route::resource('questiontopics', 'Question\QuestiontopicController');
        Route::resource('comments', 'Question\CommentController');
    });

    Route::resource('groups', 'GroupController');
    Route::group([ 'prefix' => 'groups/{group}' ], function () {
        Route::resource('groupmemberships', 'Group\GroupmembershipController');
        Route::resource('groupsubscriptions', 'Group\GroupsubscriptionController');
    });

    Route::resource('links', 'LinkController');
    Route::group([ 'prefix' => 'links/{link}' ], function () {
        Route::resource('comments', 'Link\CommentController');
    });

    Route::resource('linkdomains', 'LinkdomainController');
    Route::group([ 'prefix' => 'linkdomains/{linkdomain}' ], function () {
        Route::resource('comments', 'Linkdomain\CommentController');
    });

    Route::resource('sides', 'SideController');
    Route::group([ 'prefix' => 'sides/{side}' ], function () {
        Route::resource('comments', 'Side\CommentController');
    });

    Route::resource('topics', 'TopicController');
    Route::group([ 'prefix' => 'topics/{topic}' ], function () {
        Route::resource('comments', 'Topic\CommentController');
    });

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
