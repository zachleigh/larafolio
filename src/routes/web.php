<?php

Route::group(['middleware' => ['web', 'admin']], function () {
    Route::get('/manager', 'Larafolio\Http\Controllers\PortfolioController@index')->name('dashboard');

    Route::patch('/manager', 'Larafolio\Http\Controllers\PortfolioController@update')->name('update-portfolio');

    Route::post('/manager/session', 'Larafolio\Http\Controllers\SessionController@store')->name('store-session');

    // **PROJECTS** //
    Route::get('/manager/projects', 'Larafolio\Http\Controllers\ProjectController@index')->name('all-projects');

    Route::post('/manager/projects', 'Larafolio\Http\Controllers\ProjectController@store')->name('store-project');

    Route::get('/manager/projects/add', 'Larafolio\Http\Controllers\ProjectController@create')->name('add-project');

    Route::get('/manager/projects/{slug}/edit', 'Larafolio\Http\Controllers\ProjectController@edit')->name('edit-project');

    Route::patch('/manager/projects/{slug}/update', 'Larafolio\Http\Controllers\ProjectController@update')->name('update-project');

    Route::get('/manager/projects/{project}/images', 'Larafolio\Http\Controllers\ProjectImageController@index')->name('show-project-images');

    Route::post('/manager/projects/{project}/images', 'Larafolio\Http\Controllers\ProjectImageController@store')->name('store-project-image');

    Route::delete('/manager/projects/{slug}', 'Larafolio\Http\Controllers\ProjectController@destroy')->name('remove-project');

    Route::get('/manager/projects/{slug}', 'Larafolio\Http\Controllers\ProjectController@show')->name('show-project');

    // **PAGES** //
    Route::get('/manager/pages', 'Larafolio\Http\Controllers\PageController@index')->name('all-pages');

    Route::post('/manager/pages', 'Larafolio\Http\Controllers\PageController@store')->name('store-page');

    Route::get('/manager/pages/add', 'Larafolio\Http\Controllers\PageController@create')->name('add-page');

    Route::get('/manager/pages/{slug}/edit', 'Larafolio\Http\Controllers\PageController@edit')->name('edit-page');

    Route::patch('/manager/pages/{slug}/update', 'Larafolio\Http\Controllers\PageController@update')->name('update-page');

    Route::get('/manager/pages/{page}/images', 'Larafolio\Http\Controllers\PageImageController@index')->name('show-page-images');

    Route::post('/manager/pages/{page}/images', 'Larafolio\Http\Controllers\PageImageController@store')->name('store-page-image');

    Route::delete('/manager/pages/{slug}', 'Larafolio\Http\Controllers\PageController@destroy')->name('remove-page');

    Route::get('/manager/pages/{slug}', 'Larafolio\Http\Controllers\PageController@show')->name('show-page');

    // **TEXT BLOCKS** //
    Route::delete('/manager/blocks/{block}', 'Larafolio\Http\Controllers\TextBlockController@destroy')->name('remove-block');

    // **LINKS** //
    Route::post('/manager/links/check', 'Larafolio\Http\Controllers\LinkController@check')->name('check-link');

    Route::delete('/manager/links/{link}', 'Larafolio\Http\Controllers\LinkController@destroy')->name('remove-link');

    // **IMAGES** //
    Route::patch('/manager/images/{image}', 'Larafolio\Http\Controllers\ImageController@update')->name('update-image');

    Route::delete('/manager/images/{image}', 'Larafolio\Http\Controllers\ImageController@destroy')->name('remove-image');

    // **SETTINGS** //
    Route::get('/manager/settings/{page}', 'Larafolio\Http\Controllers\SettingsController@show')->name('show-settings');
});
