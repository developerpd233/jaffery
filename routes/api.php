<?php

namespace Api\V1\Admin;
use Illuminate\Support\Facades\Route;

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

// signup and login
Route::post('v1/signup', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@signup');
Route::post('v1/login', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@login');
Route::post('v1/social-login', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@socialLogin');
Route::post('v1/forgot-password', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@forgotPassword');
Route::post('v1/forgot-password-otpcheck', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@forgotPasswordOTPCheck');
Route::post('v1/forgot-password-reset', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@forgotPasswordreset');
Route::post('v1/reset-password', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@reset');
Route::post('v1/check-email', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@checkEmail');
Route::post('v1/check-phone', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@checkPhone');

// Home
Route::get('v1/search', 'App\Http\Controllers\Api\V1\Admin\HomeApiController@search');
Route::get('v1/home/contests', 'App\Http\Controllers\Api\V1\Admin\HomeApiController@contests');

// Contests
Route::get('v1/contests/type', 'App\Http\Controllers\Api\V1\Admin\HomeApiController@contestsList');
Route::get('v1/home/contests', 'App\Http\Controllers\Api\V1\Admin\HomeApiController@contests');

// Page
Route::get('v1/page/{slug}', 'ContentPageApiController@pageShow');

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'App\Http\Controllers\Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    
    Route::post('logout', 'AuthApiController@logout');

    // Users
    Route::get('users/show-profile', 'UsersApiController@showProfile')->name('users.showProfile');
    Route::patch('users/update-profile', 'UsersApiController@updateProfile')->name('users.updateProfile');
    Route::post('users/update-profile-image', 'UsersApiController@updateProfileImage')->name('users.updateProfileImage');
    Route::apiResource('users', 'UsersApiController');

    // Participant
    Route::apiResource('participants', 'ParticipantApiController');

    // Vote
    Route::apiResource('votes', 'VoteApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Contests
    Route::apiResource('contests', 'ContestApiController');

    // Listings
    Route::apiResource('listings', 'ListApiController');

    // Home
    Route::apiResource('home', 'HomeApiController');
});