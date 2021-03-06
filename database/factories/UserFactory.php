<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'administrator', function (Faker $faker) {
    return [
        'name' => 'JohnDoe',
    ];
});

$factory->define(\App\Thread::class, function(Faker $faker){
    $title = $faker->sentence;
    return [
        'user_id' => factory('App\User')->create()->id,
        'channel_id' => factory('App\Channel')->create()->id,
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'slug' => \Str::slug($title),
        'locked' => false,
        'visits' => 0

    ];
});

$factory->define(\App\Channel::class, function(Faker $faker){
    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => $name,
    ];
});

$factory->state(\App\Reply::class, 'withThread', [
    'user_id' => 1,
    'thread_id' => 2,
    'body' => '$faker->paragraph'
]);

$factory->define(\App\Reply::class, function(Faker $faker){
    return [
        'user_id' => factory('App\User')->create()->id,
        'thread_id' => factory('App\Thread')->create()->id,
        'body' => $faker->paragraph
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function(Faker $faker){
    return [
        'id' => Illuminate\Support\Str::uuid()->toString(),
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function() {
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});

