<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Larafolio\Models\Project::class, function (Faker\Generator $faker) {
    $name = $faker->unique()->name;

    $types = ['web', 'github', 'volunteer'];

    $index = rand(0, 2);

    return [
        'name'  => $name,
        'type'  => $types[$index],
        'link'  => $faker->url,
        'slug'  => strtolower(snake_case($name)),
        'order' => rand(0, 10),
    ];
});

$factory->define(Larafolio\Models\Image::class, function (Faker\Generator $faker) {
    $project = factory(Larafolio\Models\Project::class)->create();

    return [
        'path'       => str_random(20).'.jpg',
        'name'       => $faker->word,
        'project_id' => $project->id(),
    ];
});

$factory->define(Larafolio\Models\TextBlock::class, function (Faker\Generator $faker) {
    $text = $faker->paragraph(5, true);

    return [
        'name'           => $faker->word,
        'text'           => $text,
        'formatted_text' => $text,
        'order'          => rand(0, 10),
    ];
});

$factory->define(Larafolio\Models\Link::class, function (Faker\Generator $faker) {
    return [
        'key' => $faker->word,
        'link' => $faker->url,
    ];
});
