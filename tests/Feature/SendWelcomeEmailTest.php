<?php

namespace Tests\Feature;

use App\Mail\WelcomeEmail;
use App\Models\CollaboratorRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWelcomeEmailTest extends TestCase
{
    /** @test */
    public function tets_form_is_visible_to_human_resource_user()
    {
        // $response = $this->get('views/enviar-email');
        $response = $this->get(route('send.email'));
        $response->assertStatus(200);
        $response->assertSee('Hola');
        $response->assertSee('Enviar');
    }
    /** @test */
    public function email_must_be_valid_format()
    {
        $response = $this->post(route('welcome-email'), [
            'email' => 'test'
        ]);
        $response->assertSessionHasErrors('email');
    }
    /** @test */
    public function welcome_email_is_sent_successfully()
    {
        Mail::fake();
        $response = $this->post(route('welcome-email'), [
            'email' => 'newemployee@example.com',
            'role' => '3',
        ]);

        $response->assertJson([
            'success' => true,
        ]);

        Mail::assertQueued(WelcomeEmail::class, function ($mail) {
            return $mail->hasTo('newemployee@example.com');
        });
    }
    /** @test */
    public function system_handles_email_failure_gracefully()
    {
        Mail::fake();

        // Forzar error lanzando excepción falsa, simulando fallo en Mail::queue()
        Mail::shouldReceive('to->queue')->andThrow(new \Exception('SMTP down'));

        $response = $this->post(route('welcome-email'), [
            'email' => 'fail@example.com',
            'role' => '2',
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'message' => 'Ocurrió un error al enviar el correo.',
        ]);
    }
}
