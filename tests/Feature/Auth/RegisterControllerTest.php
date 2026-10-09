<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_register_a_regular_account(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Buat Akun Baru!');

        $this->post(route('register'), [
            'name' => 'Registered Administrator',
            'email' => 'registered@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
            'is_superadmin' => true,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Registered Administrator',
            'email' => 'registered@example.com',
            'is_superadmin' => false,
        ]);
    }

    public function test_registration_rejects_an_existing_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post(route('register'), [
            'name' => 'Duplicate Account',
            'email' => 'taken@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
        $this->assertGuest();
    }
}
