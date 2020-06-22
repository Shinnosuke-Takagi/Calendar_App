<?php

use Illuminate\Http\Request;
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
Route::get('/user', fn() => Auth::user())->name('user');

Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::post('/events', 'EventController@create')->name('event.create');
Route::get('/events', 'EventController@index')->name('event.index');
Route::patch('/events/{id}', 'EventController@update')->name('event.update');
Route::delete('/events/{id}', 'EventController@delete')->name('event.delete');
