<?php

namespace Database\Seeders;

use App\Models\Charger;
use App\Models\Customer;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $user = User::where('role_id', getRoleIdBySlug('user'))->get();

        foreach ($user as $user) {
            $customer = Customer::create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1],
                'phone_mobile' => $faker->phoneNumber,
                'phone_home' => $faker->phoneNumber,
                'address_1' => $faker->address,
                'address_2' => $faker->address,
                'address_3' => $faker->address,
                'city' => $faker->city,
                'state' => 'Kuala Lumpur',
                'zipcode' => $faker->postcode,
                'country_id' => getCountryIdByName('Malaysia'),
            ]);

            $charger = Charger::where('isActive', true)->first();

            $charger->customer()->attach($customer);
        }

        Customer::factory()->count(3)->create()->each(function ($customer) {
            $charger = Charger::where('isActive', true)->inRandomOrder()->first();
            $charger->customer()->attach($customer);
        });
    }
}
