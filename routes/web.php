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

Route::get('/', 'HomeController@index')->name('home');

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

Route::get('/tags/{tag_id}/{tag_slug?}', function (\App\Tag $tag_id, $tag_slug=null) {
  return view('tags', [
    'tag' => $tag_id,
    'films' => $tag_id->films()->paginate(20),
  ]);
})->where(['tag_id' => '[0-9]+'])->name('tags');

Route::group(['prefix' => 'search', 'as' => 'search.'], function() {
  Route::post('/films', 'SearchController@postFilms');
  Route::get('/films/{query?}', 'SearchController@films')->name('film');
});
