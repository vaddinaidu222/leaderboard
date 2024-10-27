<?php

namespace Database\Factories;

use App\Models\UserRank;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRankFactory extends Factory
{
    protected $model = UserRank::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'daily_rank' => 1, // Default values
            'monthly_rank' => 1,
            'yearly_rank' => 1,
        ];
    }
}

