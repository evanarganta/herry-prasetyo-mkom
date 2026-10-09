<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $query = trim($validated['q'] ?? '');
        $results = collect();

        if ($query !== '') {
            $like = '%'.$query.'%';

            $results = $results
                ->concat(Siswa::query()
                    ->with('kelas')
                    ->where(fn (Builder $builder) => $builder
                        ->where('nis', 'like', $like)
                        ->orWhere('nama', 'like', $like)
                        ->orWhere('no_hp', 'like', $like))
                    ->orderBy('nama')
                    ->limit(10)
                    ->get()
                    ->map(fn (Siswa $siswa): array => [
                        'type' => 'Siswa',
                        'title' => $siswa->nama,
                        'details' => 'NIS '.$siswa->nis.' - '.($siswa->kelas?->nama_kelas ?? 'Tanpa kelas'),
                        'url' => route('siswa.show', $siswa),
                    ]))
                ->concat(Guru::query()
                    ->where(fn (Builder $builder) => $builder
                        ->where('nip', 'like', $like)
                        ->orWhere('nama', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('no_hp', 'like', $like))
                    ->orderBy('nama')
                    ->limit(10)
                    ->get()
                    ->map(fn (Guru $guru): array => [
                        'type' => 'Guru',
                        'title' => $guru->nama,
                        'details' => 'NIP '.$guru->nip.' - '.($guru->email ?: $guru->no_hp ?: 'Data guru'),
                        'url' => route('guru.index'),
                    ]))
                ->concat(Kelas::query()
                    ->where(fn (Builder $builder) => $builder
                        ->where('nama_kelas', 'like', $like)
                        ->orWhere('tingkat', 'like', $like)
                        ->orWhere('jurusan', 'like', $like)
                        ->orWhere('tahun_ajaran', 'like', $like))
                    ->orderBy('nama_kelas')
                    ->limit(10)
                    ->get()
                    ->map(fn (Kelas $kelas): array => [
                        'type' => 'Kelas',
                        'title' => $kelas->nama_kelas,
                        'details' => $kelas->jurusan.' - '.$kelas->tahun_ajaran,
                        'url' => route('kelas.index'),
                    ]))
                ->concat(Mapel::query()
                    ->where(fn (Builder $builder) => $builder
                        ->where('kode_mapel', 'like', $like)
                        ->orWhere('nama_mapel', 'like', $like))
                    ->orderBy('nama_mapel')
                    ->limit(10)
                    ->get()
                    ->map(fn (Mapel $mapel): array => [
                        'type' => 'Mata Pelajaran',
                        'title' => $mapel->nama_mapel,
                        'details' => 'Kode '.$mapel->kode_mapel.' - KKM '.$mapel->kkm,
                        'url' => route('mapel.index'),
                    ]))
                ->concat(Nilai::query()
                    ->with(['siswa', 'mapel'])
                    ->where('keterangan', 'like', $like)
                    ->orWhere('nilai_tugas', 'like', $like)
                    ->orWhere('nilai_uts', 'like', $like)
                    ->orWhere('nilai_uas', 'like', $like)
                    ->orWhere('nilai_akhir', 'like', $like)
                    ->orWhereHas('siswa', fn (Builder $builder) => $builder
                        ->where('nama', 'like', $like)
                        ->orWhere('nis', 'like', $like))
                    ->orWhereHas('mapel', fn (Builder $builder) => $builder
                        ->where('nama_mapel', 'like', $like)
                        ->orWhere('kode_mapel', 'like', $like))
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(fn (Nilai $nilai): array => [
                        'type' => 'Nilai',
                        'title' => ($nilai->siswa?->nama ?? 'Siswa').' - '.($nilai->mapel?->nama_mapel ?? 'Mata pelajaran'),
                        'details' => 'Nilai akhir '.$nilai->nilai_akhir.' - '.($nilai->keterangan ?? 'Belum ada keterangan'),
                        'url' => route('nilai.index'),
                    ]));
        }

        return view('search.index', [
            'query' => $query,
            'results' => $results,
            'resultCount' => $results->count(),
        ]);
    }
}
