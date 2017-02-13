<?php

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

$factory->define(Larafolio\Models\Project::class, function () {
    $name = str_random(30);

    $types = ['web', 'github', 'volunteer'];

    $index = rand(0, 2);

    return [
        'name'  => $name,
        'type'  => $types[$index],
        'slug'  => strtolower(snake_case($name)),
        'order' => rand(0, 10),
    ];
});

$factory->define(Larafolio\Models\Image::class, function (Faker\Generator $faker) {
    $project = factory(Larafolio\Models\Project::class)->create();

    return [
        'path'        => str_random(20).'.jpg',
        'name'        => $faker->word,
        'resource_id' => $project->id,
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
        'name'  => $faker->word,
        'text'  => $faker->sentence,
        'url'   => $faker->url,
        'order' => rand(0, 10),
    ];
});

$factory->define(Larafolio\Models\Page::class, function () {
    $name = str_random(30);

    return [
        'name'  => $name,
        'slug'  => strtolower(snake_case($name)),
        'order' => rand(0, 10),
    ];
});

$factory->define(Larafolio\Models\TextLine::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->word,
        'text'           => $faker->sentence,
        'order'          => rand(0, 10),
    ];
});
