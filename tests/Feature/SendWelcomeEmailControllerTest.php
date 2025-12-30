<?php

namespace Tests\Feature;

use App\Mail\WelcomeEmail;
use App\Models\CollaboratorRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWelcomeEmailControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_shows_the_email_form_with_roles()
    {
        CollaboratorRole::factory()->count(3)->create();

        $response = $this->get(route('send.email'));

        $response->assertStatus(200);
        $response->assertViewIs('emails.send');
        $response->assertViewHas('collaboratorRoles');
    }
    /** @test */
    public function it_sends_the_welcome_email_successfully()
    {
        Mail::fake();
        $role = CollaboratorRole::factory()->create();

        $response = $this->postJson(route('welcome-email'), [
            'email' => 'candidate@example.com',
            'role' => $role->collaborator_role_id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Correo enviado correctamente.',
            ]);

        $this->assertDatabaseHas('invitation_links', [
            'email' => 'candidate@example.com',
            'fk_collaborator_role_id' => $role->collaborator_role_id,
        ]);

        Mail::assertQueued(WelcomeEmail::class, function ($mail) {
            return $mail->hasTo('candidate@example.com');
        });
    }
    /** @test */
    public function it_fails_validation_with_invalid_email()
    {
        $role = CollaboratorRole::factory()->create();

        $response = $this->postJson(route('welcome-email'), [
            'email' => 'invalid-email',
            'role' => $role->collaborator_role_id,
        ]);

        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['email']);
    }
}
