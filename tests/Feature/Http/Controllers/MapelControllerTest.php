<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapelControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_complete_subject_lifecycle(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('mapel.create'))->assertOk();
        $this->post(route('mapel.store'), [
            'kode_mapel' => 'MP-01',
            'nama_mapel' => 'Matematika',
            'kkm' => 75,
        ])->assertRedirect(route('mapel.index'));

        $mapel = Mapel::where('kode_mapel', 'MP-01')->firstOrFail();
        $this->get(route('mapel.index'))->assertOk()->assertSee('Matematika');
        $this->get(route('mapel.edit', $mapel))->assertOk();
        $this->put(route('mapel.update', $mapel), [
            'kode_mapel' => 'MP-01',
            'nama_mapel' => 'Matematika Lanjut',
            'kkm' => 80,
        ])->assertRedirect(route('mapel.index'));
        $this->assertDatabaseHas('mapels', ['id' => $mapel->id, 'nama_mapel' => 'Matematika Lanjut', 'kkm' => 80]);

        $this->delete(route('mapel.destroy', $mapel))->assertRedirect(route('mapel.index'));
        $this->assertDatabaseMissing('mapels', ['id' => $mapel->id]);
    }

    public function test_subject_used_by_a_grade_cannot_be_deleted(): void
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => 'XI',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2025/2026',
        ]);
        $siswa = Siswa::create([
            'nis' => 'S-100',
            'nama' => 'Nadia Putri',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ]);
        $mapel = Mapel::create(['kode_mapel' => 'MP-01', 'nama_mapel' => 'Matematika', 'kkm' => 75]);
        Nilai::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai_tugas' => 80,
            'nilai_uts' => 80,
            'nilai_uas' => 80,
            'nilai_akhir' => 80,
            'keterangan' => 'Lulus',
        ]);
        $this->actingAs(User::factory()->create());

        $this->delete(route('mapel.destroy', $mapel))
            ->assertRedirect(route('mapel.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('mapels', ['id' => $mapel->id]);
    }
}
