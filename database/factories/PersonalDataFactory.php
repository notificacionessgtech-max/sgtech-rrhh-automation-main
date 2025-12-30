<?php

namespace Database\Factories;

use App\Models\InvitationLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalData>
 */
class PersonalDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hiring_date' => $this->faker->date(),
            'fk_invitation_link_id' => InvitationLink::factory(), // relación
            'job_position' => $this->faker->jobTitle(),
            'dni' => $this->faker->numerify('#########'),
            'date_of_issue' => $this->faker->date(),
            'place_of_issue' => $this->faker->city(),
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'second_last_name' => $this->faker->lastName(),
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed', 'free union']),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birthdate' => $this->faker->date(),
            'place_of_birth' => $this->faker->city(),
            'nationality' => $this->faker->country(),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
            'cellphone_number' => $this->faker->numerify('3#########'),
            'email' => $this->faker->unique()->safeEmail(),
            'banking_entity' => $this->faker->randomElement(['Bancolombia', 'Davivienda', 'Nequi']),
            'account_number' => $this->faker->bankAccountNumber(),
            'account_type' => $this->faker->randomElement(['current', 'savings', 'payroll']),
            'eps' => $this->faker->word(),
            'pension_fund' => $this->faker->word(),
            'severance_pay_fund' => $this->faker->word(),
        ];
    }
}
