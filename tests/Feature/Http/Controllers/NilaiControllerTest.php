<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_and_update_a_calculated_grade(): void
    {
        $kelas = $this->createKelas();
        $siswa = $this->createSiswa($kelas);
        $mapel = $this->createMapel();
        $this->actingAs(User::factory()->create());

        $this->get(route('nilai.create'))->assertOk()->assertSee('Input Nilai Siswa');
        $this->post(route('nilai.store'), [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai_tugas' => 80,
            'nilai_uts' => 70,
            'nilai_uas' => 90,
        ])->assertRedirect(route('nilai.index'));

        $nilai = Nilai::where('siswa_id', $siswa->id)->firstOrFail();
        $this->assertSame(81.0, (float) $nilai->nilai_akhir);
        $this->assertSame('Tidak Lulus', $nilai->keterangan);
        $this->get(route('nilai.index'))->assertOk()->assertSee('Nadia Putri')->assertSee('Matematika');
        $this->get(route('nilai.edit', $nilai))->assertOk();

        $this->put(route('nilai.update', $nilai), [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai_tugas' => 100,
            'nilai_uts' => 100,
            'nilai_uas' => 100,
        ])->assertRedirect(route('nilai.index'));

        $nilai->refresh();
        $this->assertSame(100.0, (float) $nilai->nilai_akhir);
        $this->assertSame('Lulus', $nilai->keterangan);
        $this->delete(route('nilai.destroy', $nilai))->assertRedirect(route('nilai.index'));
        $this->assertDatabaseMissing('nilais', ['id' => $nilai->id]);
    }

    public function test_duplicate_subject_grade_for_a_student_is_rejected(): void
    {
        $kelas = $this->createKelas();
        $siswa = $this->createSiswa($kelas);
        $mapel = $this->createMapel();
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

        $this->post(route('nilai.store'), [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai_tugas' => 90,
            'nilai_uts' => 90,
            'nilai_uas' => 90,
        ])->assertSessionHasErrors('mapel_id');

        $this->assertDatabaseCount('nilais', 1);
    }

    private function createKelas(): Kelas
    {
        return Kelas::create([
            'nama_kelas' => 'XI RPL 1',
            'tingkat' => 'XI',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_ajaran' => '2025/2026',
        ]);
    }

    private function createSiswa(Kelas $kelas): Siswa
    {
        return Siswa::create([
            'nis' => 'S-100',
            'nama' => 'Nadia Putri',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ]);
    }

    private function createMapel(): Mapel
    {
        return Mapel::create(['kode_mapel' => 'MP-01', 'nama_mapel' => 'Matematika', 'kkm' => 85]);
    }
}
