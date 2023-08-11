<?php

use App\Mail\forgot_otp;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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
Route::get('/check-email', function(){
    $user = User::first();
    // Mail::to('muddassirizhar@gmail.com')->send(new WelcomeMail($user));
    event(new Registered($user));

    $user->update([
        'forgot_otp' => rand(0, 9999)
    ]);

    Mail::to('muddassirizhar@gmail.com')->send(new forgot_otp($user));
});
Route::get('/check-fb/{slug}', function(){

    return view('check_fb');
});

// Route::get('/update-participants', function(){

//     $Participants = Participant::all();

//     foreach ($Participants as $key => $Participant)
//     {
//         $Participant->slug = Str::slug($Participant->name).'-'.Str::random(16);
//         $Participant->save();
//     }

//     dd('done');
// });

//Route::get('cron/test', 'App\Http\Controllers\PageController@cronTest');

// Route::get('/test', function(){
//     return view('test');
// });

Route::get('upload/professions', 'App\Http\Controllers\PageController@uploadProfessions')->name('uploadProfessions');

Route::get('login/{provider}', 'App\Http\Controllers\SocialController@redirect')->name('provider');
Route::get('login/{provider}/callback','App\Http\Controllers\SocialController@Callback')->name('provider-callback');

Route::get('/', 'App\Http\Controllers\HomeController@landing')->name('landing');
Route::get('/search', 'App\Http\Controllers\HomeController@search')->name('search');
Route::get('/monthlyContest', 'App\Http\Controllers\HomeController@monthlyContest')->name('monthlyContest');
Route::get('/annualParticipants', 'App\Http\Controllers\HomeController@annualParticipants')->name('annualParticipants');
Route::get('/videoParticipants', 'App\Http\Controllers\HomeController@videoParticipants')->name('videoParticipants');
Route::get('/monthlyParticipants', 'App\Http\Controllers\HomeController@monthlyParticipants')->name('monthlyParticipants');

Route::get('/users/{id}', 'App\Http\Controllers\UserController@show')->name('user.show');
Route::post('/favourites', 'App\Http\Controllers\UserController@favouriteCreate')->name('user.favouriteCreate');
Route::post('/favourite-delete', 'App\Http\Controllers\UserController@favouriteDelete')->name('user.favouriteDelete');
Route::get('/favorites', 'App\Http\Controllers\UserController@favourites')->name('user.favourites');

Route::get('/participate/{id}', 'App\Http\Controllers\ParticipantController@participate')->name('participate');

Route::get('/contests', 'App\Http\Controllers\ContestController@index')->name('contest.index');
Route::get('/contests/{id}', 'App\Http\Controllers\ContestController@show')->name('contest.show');

//Route::get('/participant/{id}', [App\Http\Controllers\ParticipantController::class, 'show'])->name('participant.show');
Route::get('/participant/{slug}', [App\Http\Controllers\ParticipantController::class, 'show'])->name('participant.show');
Route::post('/participant', [App\Http\Controllers\ParticipantController::class, 'store'])->name('participant.store');
Route::post('/vote', [App\Http\Controllers\ParticipantController::class, 'vote'])->name('participant.vote');
Route::post('/upload_video', [App\Http\Controllers\ParticipantController::class, 'upload_video'])->name('participant.upload_video');

Route::get('/country/{name}', 'App\Http\Controllers\LocationController@countries')->name('countries');
Route::get('/state/{name}', 'App\Http\Controllers\LocationController@states')->name('states');
Route::get('/city/{name}', 'App\Http\Controllers\LocationController@cities')->name('cities');
Route::get('/profession/{name}', 'App\Http\Controllers\LocationController@professions')->name('professions');

Route::get('/contestants', [App\Http\Controllers\ParticipantController::class, 'contestants'])->name('contestants');
Route::get('/winners', [App\Http\Controllers\ParticipantController::class, 'winners'])->name('winners');

Route::get('/contact', function(){
    return view('contact');
});

Route::get('/product', function(){
    return view('product');
});

Route::post('handle-payment', [App\Http\Controllers\ParticipantController::class,'handlePayment'])->name('make.payment');
// Route::get('handle-payment', [App\Http\Controllers\ParticipantController::class,'handlePayment'])->name('make.payment');
Route::get('cancel-payment', [App\Http\Controllers\ParticipantController::class,'paymentCancel'])->name('cancel.payment');
Route::get('payment-success', [App\Http\Controllers\ParticipantController::class,'paymentSuccess'])->name('success.payment');

// paypal payment with other option
Route::get('paypalCheck/{id}', [App\Http\Controllers\ParticipantController::class,'paypalCheck'])->name('paypalCheck');
// Route::post('paypal', [App\Http\Controllers\ParticipantController::class,'paypal'])->name('paypal');
// Route::get('paypal-success', [App\Http\Controllers\ParticipantController::class,'paypalSuccess'])->name('success.paypal');
Route::post('authorizeDotNet', [App\Http\Controllers\ParticipantController::class,'authorizeDotNet'])->name('authorizeDotNet');
Route::get('authorizeDotNet-success', [App\Http\Controllers\ParticipantController::class,'authorizeDotNetSuccess'])->name('success.authorizeDotNet');


Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', function(){
    return redirect(url('/'));
})->name('home');

Route::get('/getStates/{id}', [App\Http\Controllers\LocationController::class, 'getStates'])->name('getStates');
Route::get('/getCities/{id}', [App\Http\Controllers\LocationController::class, 'getCities'])->name('getCities');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/faq', [App\Http\Controllers\PageController::class, 'faq'])->name('page.faq');
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'index'])->name('page.index');

Route::post('contact', [App\Http\Controllers\PageController::class, 'contact'])->name('page.contact');

Route::resource('comments', App\Http\Controllers\CommentController::class);




