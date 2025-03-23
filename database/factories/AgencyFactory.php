<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agency>
 */
class AgencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessType = $this->faker->randomElement(['UK', 'Europe']);

        if($businessType === 'UK') {
            $companyNumber = $this->faker->numerify('###########'); // 11 digits
        }

        if($businessType === 'Europe') {
            $companyNumber = Str::upper(Str::random(14)); // 14 alphanumeric characters
        }

        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->companyEmail,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'postcode' => $this->faker->postcode,
            'number_of_users' => $this->faker->numberBetween(0, 1000),
            'date_of_information' => $this->faker->dateTimeBetween('1975-01-01', 'now'),
            'business_type' => $businessType,
            'company_number' => $companyNumber,
        ];
    }
}
