<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_complete_teacher_lifecycle(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('guru.create'))->assertOk();
        $this->post(route('guru.store'), [
            'nip' => 'G-100',
            'nama' => 'Rina Guru',
            'jenis_kelamin' => 'P',
            'email' => 'rina@example.test',
            'no_hp' => '0812345678',
            'alamat' => 'Bandung',
        ])->assertRedirect(route('guru.index'));

        $guru = Guru::where('nip', 'G-100')->firstOrFail();
        $this->get(route('guru.index'))->assertOk()->assertSee('Rina Guru');
        $this->get(route('guru.edit', $guru))->assertOk();
        $this->put(route('guru.update', $guru), [
            'nip' => 'G-100',
            'nama' => 'Rina Utami',
            'jenis_kelamin' => 'P',
            'email' => 'rina@example.test',
            'no_hp' => '0812345678',
            'alamat' => 'Bandung',
        ])->assertRedirect(route('guru.index'));
        $this->assertDatabaseHas('gurus', ['id' => $guru->id, 'nama' => 'Rina Utami']);

        $this->delete(route('guru.destroy', $guru))->assertRedirect(route('guru.index'));
        $this->assertDatabaseMissing('gurus', ['id' => $guru->id]);
    }
}
