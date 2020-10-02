<?php

use App\Events\MessageDelivered;
use Illuminate\Support\Facades\Route;
use App\Message;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('messages', 'MessageController');
Route::post('messages/index/{room_id}', 'MessageController@index')->name('messages.index');
Route::post('messages/getmessages', 'HomeController@getmessages')->name('messages.getmessages');
Route::get('/rooms', 'MessageController@getrooms')->name('messages.rooms');
Route::get('/add-room-chat', 'MessageController@addroomform')->name('room.chat.add.form');
Route::get('/room-chat', 'MessageController@addroom')->name('room.chat.add');
Route::get('/add-user/{room_id}', 'MessageController@adduserroomform')->name('add.user');
Route::get('/add-user1', 'MessageController@adduserroom')->name('add.room.user');

Route::get('/video-chat/{room_id}', 'MessageController@videochat')->name('room.video.chat');


