<?php

namespace Tests\Feature;

use App\Models\CollaboratorRole;
use App\Models\InvitationLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class StorePersonalDataTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_stores_personal_data_and_updates_invitation()
    {
        // Arrange
        $role = CollaboratorRole::factory()->create(['collaborator_role_id' => 1]);
        $invitation = InvitationLink::factory()->create([
            'uuid' => Str::uuid(),
            'fk_collaborator_role_id' => $role->collaborator_role_id,
            'status' => 'pending'
        ]);

        // Datos simulados del formulario
        $data = [
            'invitation_uuid' => $invitation->uuid,
            'hiring_date' => now()->format('Y-m-d'),
            'job_position' => 'Backend Developer',
            'dni' => '123456789',
            'date_of_issue' => now()->subYears(5)->format('Y-m-d'),
            'place_of_issue' => 'Bogotá',
            'first_name' => 'Juan',
            'middle_name' => 'Carlos',
            'last_name' => 'Pérez',
            'second_last_name' => 'Gómez',
            'blood_group' => 'O+',
            'marital_status' => 'single',
            'gender' => 'male',
            'birthdate' => now()->subYears(25)->format('Y-m-d'),
            'place_of_birth' => 'Medellín',
            'nationality' => 'Colombiana',
            'address' => 'Calle Falsa 123',
            'phone_number' => '5555555',
            'cellphone_number' => '3001234567',
            'email' => 'juan@example.com',
            'banking_entity' => 'Bancolombia',
            'account_number' => '00012345678',
            'account_type' => 'savings',
            'eps' => 'Sura',
            'pension_fund' => 'Protección',
            'severance_pay_fund' => 'Colfondos',

            // Familiares
            'relationship' => 'Hermano',
            'family_data_dni' => '987654321',
            'full_name' => 'Pedro Pérez',
            'age' => 30,
            'family_data_birthdate' => now()->subYears(30)->format('Y-m-d'),

            // Salud
            'allergies' => 'Ninguna',
            'diseases' => 'Ninguna',
            'medications' => 'Ninguno',
            'additional_information' => 'N/A',

            // Contacto de emergencia
            'emergency_contact_full_name' => 'María Pérez',
            'emergency_contact_phone_number' => '3100000000',
            'emergency_contact_relationship' => 'Madre',

            // Académica
            'academic_institution' => 'Universidad Nacional',
            'start_date' => now()->subYears(5)->format('Y-m-d'),
            'end_date' => now()->subYears(1)->format('Y-m-d'),
            'university_career' => 'Ingeniería de Sistemas',
            'degree' => 'Ingeniero',
            'card_number' => 'ABC123',

            // Especialización
            'course' => 'Backend Avanzado',
            'specialty_start_date' => now()->subYears(2)->format('Y-m-d'),
            'specialty_end_date' => now()->subYear()->format('Y-m-d'),
            'specialty_academic_institution' => 'Platzi',
            'specialty_level' => 'Avanzado',

            // IT
            'technology' => 'Laravel',
            'knowledge_level' => 'intermediate',

            // Idiomas
            'languages' => 'Inglés',
            'language_level' => 'B2',
        ];

        // Act
        $response = $this->post(route('hiring.post'), $data);

        // Assert
        $response->assertRedirect(); // Si redirige
        $this->assertDatabaseHas('personal_data', ['email' => 'juan@example.com']);
        $this->assertDatabaseHas('family_data', ['full_name' => 'Pedro Pérez']);
        $this->assertDatabaseHas('emergency_contacts', ['full_name' => 'María Pérez']);
        $this->assertDatabaseHas('invitation_links', [
            'uuid' => $invitation->uuid,
            'status' => 'used'
        ]);
    }
}
