<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_complete_student_lifecycle(): void
    {
        $kelas = $this->createKelas();
        $this->actingAs(User::factory()->create());

        $this->get(route('siswa.create'))->assertOk()->assertSee('Tambah Data Siswa');
        $this->post(route('siswa.store'), [
            'nis' => 'S-100',
            'nama' => 'Nadia Putri',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
            'no_hp' => '0812345678',
            'alamat' => 'Bandung',
        ])->assertRedirect(route('siswa.index'));

        $siswa = Siswa::where('nis', 'S-100')->firstOrFail();
        $this->assertDatabaseHas('siswas', ['nis' => 'S-100', 'nama' => 'Nadia Putri']);
        $this->get(route('siswa.show', $siswa))->assertOk()->assertSee('Kelas XI RPL 1');
        $this->put(route('siswa.update', $siswa), [
            'nis' => 'S-100',
            'nama' => 'Nadia Utami',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
            'no_hp' => '0812345678',
            'alamat' => 'Bandung',
        ])->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswas', ['id' => $siswa->id, 'nama' => 'Nadia Utami']);

        $this->delete(route('siswa.destroy', $siswa))->assertRedirect(route('siswa.index'));
        $this->assertDatabaseMissing('siswas', ['id' => $siswa->id]);
    }

    public function test_duplicate_student_number_is_rejected(): void
    {
        $kelas = $this->createKelas();
        Siswa::create([
            'nis' => 'S-100',
            'nama' => 'Nadia Putri',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ]);
        $this->actingAs(User::factory()->create());

        $this->post(route('siswa.store'), [
            'nis' => 'S-100',
            'nama' => 'Nadia Lain',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ])->assertSessionHasErrors('nis');

        $this->assertDatabaseCount('siswas', 1);
    }

    private function createKelas(): Kelas
    {
        return Kelas::create([
            'nama_kelas' => 'Kelas XI RPL 1',
            'tingkat' => 'XI',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2025/2026',
        ]);
    }
}
