<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        // The /forgot-password endpoint accepts username + new password (no email token),
        // immediately updates the password and auto-logs the user in.
        $user = User::factory()->create();

        $response = $this->post('/forgot-password', [
            'username' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertAuthenticated();
        // Pelapor users are redirected to their dashboard with auth_init flag
        $response->assertRedirect(route('pelapor.dashboard', ['auth_init' => 1]));
        $this->assertTrue($user->fresh()->password === 'new-password' || \Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/reset-password/sample-token');

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create();
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        // After reset the user is auto-logged-in and redirected to their dashboard
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('pelapor.dashboard', ['auth_init' => 1]));

        $this->assertTrue($user->fresh()->password === 'new-password' || \Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
    }
}
