<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $response = Http::get('https://restcountries.com/v2/all?fields=name,callingCodes,alpha2Code,alpha3Code,currencies');

        if ($response->successful()) {
            $countries = $response->json();

            foreach ($countries as $countryData) {
                Country::create([
                    'country' => $countryData['name'],
                    'alpha2_code' => $countryData['alpha2Code'],
                    'alpha3_code' => $countryData['alpha3Code'],
                    'calling_code' => $countryData['callingCodes'][0] ?? null,
                    'currency_code' => $countryData['currencies'][0]['code'] ?? null,
                    'currency_name' => $countryData['currencies'][0]['name'] ?? null,
                    'currency_symbol' => $countryData['currencies'][0]['symbol'] ?? null,
                ]);
            }
        }
    }
}
