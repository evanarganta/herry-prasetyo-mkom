<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_view_users_and_create_a_regular_account(): void
    {
        $superadmin = $this->createSuperadmin();
        $this->actingAs($superadmin);

        $this->get(route('users.index'))
            ->assertOk()
            ->assertSee(route('users.index'))
            ->assertSee('Manajemen Pengguna')
            ->assertSee('Superadmin');

        $this->post(route('users.store'), [
            'name' => 'New Administrator',
            'email' => 'new-admin@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
            'is_superadmin' => true,
        ])->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $newUser = User::where('email', 'new-admin@example.com')->firstOrFail();
        $this->assertFalse($newUser->is_superadmin);
        $this->assertTrue(Hash::check('strong-password', $newUser->password));
        $this->assertDatabaseHas('users', [
            'id' => $newUser->id,
            'is_superadmin' => false,
        ]);
    }

    public function test_regular_user_cannot_access_account_management_or_see_its_sidebar_link(): void
    {
        $regularUser = User::factory()->create();
        $this->actingAs($regularUser);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee(route('users.index'));

        $this->get(route('users.index'))->assertForbidden();
        $this->post(route('users.store'), [
            'name' => 'Unauthorized Account',
            'email' => 'unauthorized@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'unauthorized@example.com']);
    }

    public function test_superadmin_can_delete_a_regular_account(): void
    {
        $this->actingAs($this->createSuperadmin());
        $regularUser = User::factory()->create();

        $this->delete(route('users.destroy', $regularUser))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $regularUser->id]);
    }

    public function test_superadmin_cannot_create_an_account_with_an_existing_email(): void
    {
        $this->actingAs($this->createSuperadmin());
        User::factory()->create(['email' => 'existing@example.com']);

        $this->post(route('users.store'), [
            'name' => 'Duplicate Account',
            'email' => 'existing@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 2);
    }

    public function test_superadmin_account_cannot_be_deleted(): void
    {
        $this->actingAs($this->createSuperadmin());
        $protectedSuperadmin = $this->createSuperadmin();

        $this->delete(route('users.destroy', $protectedSuperadmin))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertModelExists($protectedSuperadmin);
    }

    public function test_guest_is_redirected_from_account_management(): void
    {
        $this->get(route('users.index'))->assertRedirect(route('login'));
    }

    private function createSuperadmin(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['is_superadmin' => true])->save();

        return $user;
    }
}
