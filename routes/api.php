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
Route::post('v1/register-otp-check', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@registerVerifyOTP');
Route::post('v1/forgot-password-reset', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@forgotPasswordreset');
Route::post('v1/reset-password', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@reset');
Route::post('v1/check-email', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@checkEmail');
// Route::post('v1/check-phone', 'App\Http\Controllers\Api\V1\Admin\AuthApiController@checkPhone');


// Home
Route::get('v1/search', 'App\Http\Controllers\Api\V1\Admin\HomeApiController@search');
Route::get('v1/home/contests', 'App\Http\Controllers\Api\V1\Admin\HomeApiController@contests');

// Contests
Route::get('v1/contests-by-type/{type}', 'App\Http\Controllers\Api\V1\Admin\ContestApiController@index');
Route::get('v1/contest-search', 'App\Http\Controllers\Api\V1\Admin\ContestApiController@search');

// Listings
Route::get('v1/list/search', 'App\Http\Controllers\Api\V1\Admin\ListApiController@search');

// Page
Route::get('v1/page/{id}', 'App\Http\Controllers\Api\V1\Admin\PageApiController@show');

// Location
Route::get('v1/location/country', 'App\Http\Controllers\Api\V1\Admin\LocationApiController@country');
Route::get('v1/location/state/{id}', 'App\Http\Controllers\Api\V1\Admin\LocationApiController@state');
Route::get('v1/location/city/{id}', 'App\Http\Controllers\Api\V1\Admin\LocationApiController@city');
Route::get('v1/location/profession', 'App\Http\Controllers\Api\V1\Admin\LocationApiController@profession');

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'App\Http\Controllers\Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {

    Route::post('logout', 'AuthApiController@logout');

    Route::get('single-contest/{id}', 'ContestApiController@show');
    Route::get('list-participants', 'ListApiController@listParticipants');
    Route::get('contestants', 'ListApiController@contestants');
    Route::get('winners', 'ListApiController@winners');

    // Listings
    Route::get('favourites', 'ListApiController@favourites');
    Route::get('add-favourite', 'ListApiController@addFavourite');
    Route::get('remove-favourite', 'ListApiController@removeFavourite');

    // Users
    Route::get('users/show-profile', 'UsersApiController@showProfile')->name('users.showProfile');
    Route::patch('users/update-profile', 'UsersApiController@updateProfile')->name('users.updateProfile');
    Route::post('users/update-profile-image', 'UsersApiController@updateProfileImage')->name('users.updateProfileImage');
    Route::apiResource('users', 'UsersApiController');

    // Participant
    Route::post('participate/upload-image', 'ParticipantApiController@uploadImage');
    Route::post('participate/upload-video', 'ParticipantApiController@uploadVideo');
    Route::post('participant/vote', 'ParticipantApiController@vote');
    Route::get('participants/{id}', 'ParticipantApiController@show');

    // // Vote
    // Route::apiResource('votes', 'VoteApiController');

    // Comment
    Route::apiResource('comments', 'CommentApiController');
});
