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
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\GuestRecord::class, function (Faker\Generator $faker) {

    return [
        'member_id' => random_int(1,200),
        'user_id' => random_int(1,2),
        'payment_method' => random_int(1,2) == 1 ? "account" : 'cash',
        'member_signature' => 'member.png',
        'guest_signature' => 'guest.png',
        'price' => random_int(1,80),
        'created_at' => $faker->dateTimeThisMonth($max = 'now', $timezone = date_default_timezone_get())
    ];
});

$factory->define(App\Guest::class, function (Faker\Generator $faker) {

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'city' => $faker->city,
        'type' => random_int(1,2) == 1 ? "adult" : 'child',
    ];
});

$factory->define(App\Member::class, function (Faker\Generator $faker) {

    return [
        'address_line_1' => $faker->buildingNumber . " " . $faker->streetName,
        'city' => $faker->city,
        'state' => 'CT',
        'zip' => '064'.random_int(10,99),
        'current_balance' => 0.0
    ];
});

$factory->define(App\Adult::class, function (Faker\Generator $faker) {

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName
    ];
});

$factory->define(App\Child::class, function (Faker\Generator $faker) {

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birth_year' => random_int(1995,2016)
    ];
});

$factory->define(App\Phone::class, function (Faker\Generator $faker) {

    return [
        'number' => $faker->randomNumber($nbDigits = 9),
        'description' => $faker->word,
    ];
});

$factory->define(App\Email::class, function (Faker\Generator $faker) {

    return [
        'address' => $faker->email,
        'description' => $faker->word,
    ];
});

$factory->define(App\MemberRecord::class, function (Faker\Generator $faker) {

    $id = random_int(1,200);
    $member = App\Member::find($id);
    $max = $member->numPeople();

    return [
        'member_id' => $id,
        'user_id' => random_int(1,2),
        'num_members' => random_int(1,$max),
        'created_at' => $faker->dateTimeThisMonth($max = 'now', $timezone = date_default_timezone_get())
    ];
});
