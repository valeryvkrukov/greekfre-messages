<?php

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

Auth::routes();



Route::get('/', 'HomeController@index')->name('home');

Route::get('/messages', 'MessagesController@getMessages')->name('m-get');
Route::post('/messages', 'MessagesController@addMessage')->name('m-add');
Route::put('/messages', 'MessagesController@updateMessage')->name('m-update');
Route::delete('/messages/{message}/delete', 'MessagesController@deleteMessage')->name('m-delete');

Route::get('/messages/status', 'MessagesController@deliveryTrack')->name('d-stat');
//Route::post('/messages/twilio-callback', 'MessagesController@twilioCallback');

Route::get('/profile', 'ProfileController@index')->name('profile');

Route::get('/profile/load', 'ProfileController@getProfile')->name('p-get');
Route::post('/profile', 'ProfileController@updateProfile')->name('p-update');

Route::middleware('guest')->post('/delivery/status', 'DeliveryController@callback')->name('d-callback');
