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

Route::get('/messages', 'MessagesController@getMessages');
Route::post('/messages', 'MessagesController@addMessage');
Route::put('/messages', 'MessagesController@updateMessage');
Route::delete('/messages/{message}/delete', 'MessagesController@deleteMessage');

Route::get('/messages/status', 'MessagesController@deliveryTrack');
//Route::post('/messages/twilio-callback', 'MessagesController@twilioCallback');

Route::get('/profile', 'ProfileController@index')->name('profile');

Route::get('/profile/load', 'ProfileController@getProfile');
Route::post('/profile', 'ProfileController@updateProfile');
