<?php

namespace Database\Factories;

use App\Models\CustomerCharger;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerChargerSession>
 */
class CustomerChargerSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $dayOffset = 0;

        $date = now()->addDays($dayOffset);
        $dayOffset++;

        return [
            'customer_charger_id' => CustomerCharger::first()->id,
            'time_initiated' => $date,
            'time_started' => $date->copy()->addMinutes(1),
            'time_ended' => $date->copy()->addHours(2),
            'soc_percent' => 100,
            'soc_updated_at' => $date->copy()->addHours(2),
            'total_charge_amount' => rand(10, 20),
            'total_charge_kwh' => 14,
            'total_charge_duration' => 2,
            'status' => 'completed',
        ];
    }
}
