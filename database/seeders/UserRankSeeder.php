<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRank;

class UserRankSeeder extends Seeder
{
    public function run()
    {
        UserRank::factory()->count(10)->create(); // Create 10 dummy user ranks
    }
}

