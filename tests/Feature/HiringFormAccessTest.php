<?php

namespace Tests\Feature;

use App\Models\InvitationLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HiringFormAccessTest extends TestCase
{
    /** @test */
    public function it_shows_the_form_if_the_invitation_is_valid()
    {
        $invitation = InvitationLink::factory()->create([
            'expires_at' => now()->addDay(),
            'status' => 'pending',
        ]);

        $response = $this->get(route('hiring.form', $invitation->uuid));
        $response->assertStatus(200);
        $response->assertViewIs('layouts.register');
        $response->assertViewHas('invitation');
    }
    /** @test */
    public function it_returns_410_if_invitation_is_expired()
    {
        $invitation = InvitationLink::factory()->create([
            'expires_at' => now()->subMinutes(1),
            'status' => 'pending',
        ]);

        $response = $this->get(route('hiring.form', $invitation->uuid));
        $response->assertStatus(410);
        $response->assertViewIs('errors.link_expired');
    }
    /** @test */
    public function it_returns_403_if_invitation_is_already_used()
    {
        $invitation = InvitationLink::factory()->create([
            'expires_at' => now()->addDay(),
            'status' => 'used',
        ]);

        $response = $this->get(route('hiring.form', $invitation->uuid));
        $response->assertStatus(403);
        $response->assertViewIs('errors.link_already_used');
    }
}
