<?php

namespace Database\Factories;

use App\Models\FamilyData;
use App\Models\PersonalData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FamilyDataFactory extends Factory
{
    protected $model = FamilyData::class;

    public function definition(): array
    {
        return [
            'family_data_id' => $this->faker->randomNumber(),
            'relationship' => $this->faker->word(),
            'dni' => $this->faker->word(),
            'full_name' => $this->faker->name(),
            'age' => $this->faker->word(),
            'gender' => $this->faker->word(),
            'birthdate' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'personal_data_id' => PersonalData::factory(),
        ];
    }
}
