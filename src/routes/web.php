<?php

Route::group(['middleware' => ['web', 'admin']], function () {
    Route::get('/manager/add', 'Larafolio\Http\Controllers\ProjectController@create')->name('add-project');

    Route::get('/manager', 'Larafolio\Http\Controllers\DashboardController@index')->name('dashboard');

    Route::post('/manager', 'Larafolio\Http\Controllers\ProjectController@store')->name('store-project');

    Route::patch('/manager', 'Larafolio\Http\Controllers\PortfolioController@update')->name('update-portfolio');

    Route::get('/manager/{slug}/edit', 'Larafolio\Http\Controllers\ProjectController@edit')->name('edit-project');

    Route::get('/manager/projects', 'Larafolio\Http\Controllers\ProjectController@index')->name('all-projects');

    Route::patch('/manager/{slug}/update', 'Larafolio\Http\Controllers\ProjectController@update')->name('update-project');

    Route::delete('/manager/{slug}', 'Larafolio\Http\Controllers\ProjectController@destroy')->name('remove-project');

    Route::get('/manager/{project}/images', 'Larafolio\Http\Controllers\ProjectImageController@index')->name('show-images');

    Route::post('/manager/{project}/images', 'Larafolio\Http\Controllers\ProjectImageController@store')->name('store-image');

    Route::delete('/manager/blocks/{block}', 'Larafolio\Http\Controllers\TextBlockController@destroy')->name('remove-block');

    Route::post('/manager/links/check', 'Larafolio\Http\Controllers\LinkController@check')->name('check-link');

    Route::delete('/manager/links/{link}', 'Larafolio\Http\Controllers\LinkController@destroy')->name('remove-link');

    Route::patch('/manager/images/{image}', 'Larafolio\Http\Controllers\ImageController@update')->name('update-image');

    Route::delete('/manager/images/{image}', 'Larafolio\Http\Controllers\ImageController@destroy')->name('remove-image');

    Route::post('/manager/session', 'Larafolio\Http\Controllers\SessionController@store')->name('store-session');

    Route::get('/manager/settings/{page}', 'Larafolio\Http\Controllers\SettingsController@show')->name('show-settings');

    Route::get('/manager/{slug}', 'Larafolio\Http\Controllers\ProjectController@show')->name('show-project');
});
