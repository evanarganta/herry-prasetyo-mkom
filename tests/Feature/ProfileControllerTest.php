<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_their_profile_and_password(): void
    {
        $user = User::factory()->create(['email' => 'before@example.com']);
        $this->actingAs($user);

        $this->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya')
            ->assertSee(route('profile.update'))
            ->assertSee('before@example.com');

        $this->put(route('profile.update'), [
            'name' => 'Updated Admin',
            'email' => 'after@example.com',
            'current_password' => 'password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Admin',
            'email' => 'after@example.com',
            'email_verified_at' => null,
        ]);
        $this->assertTrue(Hash::check('new-secret-password', $user->fresh()->password));
    }

    public function test_profile_update_requires_the_current_password_before_changing_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'incorrect-password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
