<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agency::factory()->count(10)->create(); // Generate 10 fake agencies
    }

    /**
     * Generate a company number based on the country.
     *
     * @param string $country
     * @return string
     */
    private function generateCompanyNumber($country)
    {
        if ($country === 'UK') {
            return (string) rand(10000000000, 99999999999); // 11-digit numeric
        }

        return Str::upper(Str::random(14)); // 14-character alphanumeric
    }
}
