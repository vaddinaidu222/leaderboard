<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserActivity;

class UserActivitySeeder extends Seeder
{
    public function run()
    {
        UserActivity::factory()->count(20)->create(); // Create 20 dummy user activities
    }
}

