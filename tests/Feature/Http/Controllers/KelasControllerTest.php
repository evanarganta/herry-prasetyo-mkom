<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_complete_class_lifecycle(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('kelas.create'))->assertOk();
        $this->post(route('kelas.store'), [
            'nama_kelas' => 'X RPL 1',
            'tingkat' => 'X',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2026/2027',
        ])->assertRedirect(route('kelas.index'));

        $kelas = Kelas::where('nama_kelas', 'X RPL 1')->firstOrFail();
        $this->get(route('kelas.index'))->assertOk()->assertSee('X RPL 1')->assertSee('0');
        $this->get(route('kelas.edit', $kelas))->assertOk();
        $this->put(route('kelas.update', $kelas), [
            'nama_kelas' => 'X RPL 2',
            'tingkat' => 'X',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2026/2027',
        ])->assertRedirect(route('kelas.index'));
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id, 'nama_kelas' => 'X RPL 2']);

        $this->delete(route('kelas.destroy', $kelas))->assertRedirect(route('kelas.index'));
        $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
    }

    public function test_class_with_students_cannot_be_deleted(): void
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => 'XI',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2025/2026',
        ]);
        Siswa::create([
            'nis' => 'S-100',
            'nama' => 'Nadia Putri',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ]);
        $this->actingAs(User::factory()->create());

        $this->delete(route('kelas.destroy', $kelas))
            ->assertRedirect(route('kelas.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);
    }
}
