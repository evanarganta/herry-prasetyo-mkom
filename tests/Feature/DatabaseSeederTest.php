<?php

namespace Tests\Feature;

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
    }
}
