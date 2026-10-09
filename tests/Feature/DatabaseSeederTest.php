<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\SiswaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_academic_seeders_can_be_run_more_than_once(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);
        $this->seed(SiswaSeeder::class);
        $this->seed(SiswaSeeder::class);

        $this->assertDatabaseCount('kelas', 3);
        $this->assertDatabaseCount('siswas', 5);
        $this->assertDatabaseCount('gurus', 2);
        $this->assertDatabaseCount('mapels', 3);
        $this->assertDatabaseCount('nilais', 3);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com',
            'is_superadmin' => true,
        ]);
    }

    public function test_database_seeder_does_not_create_a_second_superadmin(): void
    {
        $existingSuperadmin = User::factory()->create();
        $existingSuperadmin->forceFill(['is_superadmin' => true])->save();

        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'id' => $existingSuperadmin->id,
            'is_superadmin' => true,
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com',
            'is_superadmin' => false,
        ]);
        $this->assertSame(1, User::where('is_superadmin', true)->count());
    }
}
