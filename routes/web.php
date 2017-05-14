<?php
Route::resource('board', 'BoardsController');
Route::resource('charts', 'ChartsController');

Auth::routes();
Route::get('/password/change', 'Auth\UpdatePasswordController@index');
Route::post('/password/change', 'Auth\UpdatePasswordController@update');

Route::get('/', 'ChartsController@index');
Route::get('/sprint/{slug}', 'ChartsController@showslug');
Route::get('/chart/{slug}/create', 'ChartsController@create');
Route::get('/api/burndown/{boardId}', 'ChartsController@burndown');

Route::get('/chart/{slug}/update', 'JiraController@updatechart');
Route::get('/api/{slug}/store', 'JiraController@store');
Route::get('/api/{slug}/update', 'JiraController@update');

Route::get('/{slug}', 'ChartsController@show');
