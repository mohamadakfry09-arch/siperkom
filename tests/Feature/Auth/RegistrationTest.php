<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $this->assertAuthenticated();
        // Registration redirects to pelapor dashboard with auth_init query parameter
        $response->assertRedirect(route('pelapor.dashboard', ['auth_init' => 1]));
    }

    public function test_registration_fails_if_password_does_not_meet_requirements(): void
    {
        // Gagal jika kurang dari 8 karakter atau tanpa simbol / angka / huruf kapital
        $response = $this->post('/register', [
            'name' => 'Weak Password User',
            'email' => 'weak@example.com',
            'password' => 'weakpass',
            'password_confirmation' => 'weakpass',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }
}
