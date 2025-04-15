<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'first_name' => explode(' ', $user->name)[0],
            'last_name' => explode(' ', $user->name)[1],
            'phone_mobile' => $this->faker->phoneNumber,
            'phone_home' => $this->faker->phoneNumber,
            'address_1' => $this->faker->address,
            'address_2' => $this->faker->address,
            'address_3' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'zipcode' => $this->faker->postcode,
            'country_id' => getCountryIdByName('Malaysia'),
        ];
    }
}
