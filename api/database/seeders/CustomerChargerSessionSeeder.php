<?php

namespace Database\Seeders;

use App\Models\CustomerChargerSession;
use Illuminate\Database\Seeder;

class CustomerChargerSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerChargerSession::factory()->count(10)->create();
    }
}
