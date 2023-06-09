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

use App\EmployeeDetails;
use App\User;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Notice::class, function (Faker\Generator $faker) {
    return [
        'heading' => $faker->realText(70),
        'description' => $faker->realText(1000),
        'created_at' => $faker->randomElement([date('Y-m-d', strtotime( '+'.mt_rand(0,7).' days')),$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]),
    ];
});


    $startDate = \Carbon\Carbon::now()->subMonth($faker->numberBetween(1, 6));
    $currencyId = \App\Currency::first()->id;

    $clientId = \App\User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->select('users.id', 'users.name', 'client_details.company_name', 'users.email', 'users.created_at')
        ->where('roles.name', 'client')
        ->inRandomOrder()
        ->first();

    return [
        'project_name' => $faker->unique()->randomElement($projectArray),
        'project_summary' => $faker->paragraph,
        'start_date' => $startDate->format('Y-m-d'),
        'deadline' => $startDate->addMonth(4)->format('Y-m-d'),
        'notes' => $faker->paragraph,
        'currency_id' => $currencyId,
        'client_id' => $clientId->id,
        'completion_percent' => $faker->numberBetween(40, 100),
        'feedback' => $faker->realText(200),
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {

    $types = \App\TicketType::all()->pluck('id')->toArray();
    $users = User::all()->pluck('id')->toArray();
    $channels = \App\TicketChannel::all()->pluck('id')->toArray();
    $agents = User::select('users.id as id')
        ->join('employee_details', 'users.id', '=', 'employee_details.user_id')
        ->join('role_user', 'role_user.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'role_user.role_id')
        ->where('roles.name', 'employee')
        ->inRandomOrder()
        ->get()->pluck('id')->toArray();

    return [
        'subject' => $faker->realText(70),
        'status' => $faker->randomElement(['open', 'pending', 'resolved', 'closed']),
        'priority' => $faker->randomElement(['low', 'high', 'medium', 'urgent']),
        'user_id' => $faker->randomElement($users),
        'agent_id' => $faker->randomElement($agents),
        'channel_id' => $faker->randomElement($channels),
        'type_id' => $faker->randomElement($types),
        'created_at' => $faker->randomElement([date('Y-m-d', strtotime( '+'.mt_rand(0, 7).' days')), $faker->dateTimeThisYear($max = 'now')]),
        'updated_at' => $faker->randomElement([date('Y-m-d', strtotime( '+'.mt_rand(0, 7).' days')), $faker->dateTimeThisYear($max = 'now')]),
    ];
});


$factory->define(App\Event::class, function (Faker\Generator $faker) {
    return [
        'event_name' => $faker->text(20),
        'label_color' => $faker->randomElement(['bg-info', 'bg-warning', 'bg-purple', 'bg-danger', 'bg-success', 'bg-inverse']),
        'where' => $faker->address,
        'description' => $faker->paragraph,
        'start_date_time' => $start = $faker->randomElement([$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]),
        'end_date_time' => $faker->dateTimeBetween($start, $start->add(new DateInterval('PT10H30S'))),
        'repeat' => 'no',
    ];
});

$factory->define(App\Expense::class, function (Faker\Generator $faker) {
    $employees = EmployeeDetails::all()->pluck('user_id')->toArray();
    return [
        'item_name' => $faker->text(20),
        'purchase_date' => $start = $faker->randomElement([$faker->dateTimeThisMonth($max = 'now'), $faker->dateTimeThisYear($max = 'now')]),
        'purchase_from' => $faker->realText(10),
        'price' => $faker->numberBetween(100, 1000),
        'currency_id' => 1,
        'user_id' => $faker->randomElement($employees),
        'status' => $faker->randomElement(['approved', 'pending', 'rejected']),
    ];
});