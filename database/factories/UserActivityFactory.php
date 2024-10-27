<?php

namespace Database\Factories;

use App\Models\UserActivity;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserActivityFactory extends Factory
{
    protected $model = UserActivity::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'activity_id' => Activity::factory(),
            'activity_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

