<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('ja_JP');

        return [
            'name' => $faker->company,
            'color' => $faker->hexColor,
        ];
    }
}
