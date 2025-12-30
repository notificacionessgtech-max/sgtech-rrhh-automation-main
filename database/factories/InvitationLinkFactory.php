<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvitationLink>
 */
class InvitationLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'email' => $this->faker->safeEmail,
            'fk_collaborator_role_id' => 1,
            'status' => 'pending',
            'expires_at' => now()->addDays(2),
        ];
    }
}
