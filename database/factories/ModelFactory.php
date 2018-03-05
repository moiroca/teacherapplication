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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'role'	=> rand(1,2),
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Teacher::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'role'	=> 1,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Student::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'role'	=> 2,
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Subject::class, function (Faker\Generator $faker) {
	$teacher = factory(App\Models\Teacher::class)->create();

    return [
        'name' => $faker->words(3, true),
        'teacher_id' => $teacher->id
    ];
});

$factory->define(App\Models\Quiz::class, function (Faker\Generator $faker) {
	$subject = factory(App\Models\Subject::class)->create();

    return [
        'title' => $faker->words(5, true),
        'subject_id' => $subject->id
    ];
});