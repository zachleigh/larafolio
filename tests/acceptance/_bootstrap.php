<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

require __DIR__.'/../../vendor/laravel/laravel/bootstrap/autoload.php';

$app = require __DIR__.'/../../vendor/laravel/laravel/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$app->make(EloquentFactory::class)->load(__DIR__.'/../../src/database/factories/');

// $app['config']->set('database.default', 'sqlite');

// $app['config']->set(
//     'database.connections.sqlite.database',
//     'database.sqlite'
// );

Artisan::call('migrate:refresh');

Artisan::call('db:seed', [
    '--class' => 'Larafolio\database\seeds\DatabaseSeeder',
]);
