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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {

});

Route::group(['prefix' => 'watch', 'as' => 'watch.'], function() {
  Route::get('/', 'WatchController@index')
    ->name('index');

  Route::get('/{film_id}/{film_slug?}', 'WatchController@watchFilm')
    ->where(['film_id' => '\s*[0-9]+'])
    ->name('film');

  Route::get('/{film_id}/{episode_id}/{film_slug?}/{episode_slug?}', 'WatchController@watchEpisode')
    ->where(['film_id' => '\s*[0-9]+', 'episode_id' => '\s*[0-9]+'])
    ->name('episode');
});
