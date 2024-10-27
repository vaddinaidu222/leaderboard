<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),
            'points' => 20, // Fixed points for each activity
        ];
    }
}

