<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_guest_cannot_submit_academic_data(): void
    {
        $this->post(route('siswa.store'), [])->assertRedirect(route('login'));
    }

    public function test_login_page_and_registration_form_are_available(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Selamat Datang Kembali!');
        $this->get(route('register'))->assertOk()->assertSee('Buat Akun Baru!');
    }

    public function test_seeded_admin_can_log_in(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->post(route('login'), [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Akademik');
    }
}
