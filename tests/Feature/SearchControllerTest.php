<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_search_all_academic_data(): void
    {
        $kelas = Kelas::create([
            'nama_kelas' => 'Arunika RPL',
            'tingkat' => 'XI',
            'jurusan' => 'Arunika',
            'tahun_ajaran' => '2025/2026',
        ]);
        $siswa = Siswa::create([
            'nis' => 'S-ARUNIKA',
            'nama' => 'Arunika Student',
            'jenis_kelamin' => 'P',
            'kelas_id' => $kelas->id,
        ]);
        Guru::create([
            'nip' => 'G-ARUNIKA',
            'nama' => 'Arunika Teacher',
            'jenis_kelamin' => 'L',
        ]);
        $mapel = Mapel::create([
            'kode_mapel' => 'M-ARUNIKA',
            'nama_mapel' => 'Arunika Mathematics',
            'kkm' => 75,
        ]);
        Nilai::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai_tugas' => 80,
            'nilai_uts' => 80,
            'nilai_uas' => 80,
            'nilai_akhir' => 80,
            'keterangan' => 'Lulus Arunika',
        ]);
        $this->actingAs(User::factory()->create());

        $this->get(route('search', ['q' => 'Arunika']))
            ->assertOk()
            ->assertSee('Ditemukan 5 hasil')
            ->assertSee(route('search'))
            ->assertSee('Arunika Student')
            ->assertSee('Arunika Teacher')
            ->assertSee('Arunika RPL')
            ->assertSee('Arunika Mathematics')
            ->assertSee('Nilai akhir 80');
    }

    public function test_search_input_is_escaped_and_authenticated(): void
    {
        $this->get(route('search', ['q' => '<script>']))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('search', ['q' => '<script>']))
            ->assertOk()
            ->assertSee('<script>')
            ->assertDontSee('<script>', false);
    }
}
