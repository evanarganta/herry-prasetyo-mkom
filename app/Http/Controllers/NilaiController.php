<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NilaiController extends Controller
{
    public function index(): View
    {
        $nilais = Nilai::with(['siswa.kelas', 'mapel'])->latest()->get();

        return view('nilai.index', compact('nilais'));
    }

    public function create(): View
    {
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('nilai.create', compact('siswas', 'mapels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswas,id'],
            'mapel_id' => [
                'required',
                'integer',
                'exists:mapels,id',
                Rule::unique('nilais', 'mapel_id')->where('siswa_id', (int) $request->input('siswa_id')),
            ],
            'nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai_uas' => ['required', 'numeric', 'between:0,100'],
        ]);

        Nilai::create($this->withCalculatedGrade($validated));

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil disimpan.');
    }

    public function edit(Nilai $nilai): View
    {
        $siswas = Siswa::with('kelas')->orderBy('nama')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('nilai.edit', compact('nilai', 'siswas', 'mapels'));
    }

    public function update(Request $request, Nilai $nilai): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswas,id'],
            'mapel_id' => [
                'required',
                'integer',
                'exists:mapels,id',
                Rule::unique('nilais', 'mapel_id')
                    ->where('siswa_id', (int) $request->input('siswa_id'))
                    ->ignore($nilai->id),
            ],
            'nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai_uas' => ['required', 'numeric', 'between:0,100'],
        ]);

        $nilai->update($this->withCalculatedGrade($validated));

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil dihapus.');
    }

    /**
     * @param  array{siswa_id: int|string, mapel_id: int|string, nilai_tugas: int|float|string, nilai_uts: int|float|string, nilai_uas: int|float|string}  $validated
     * @return array{siswa_id: int|string, mapel_id: int|string, nilai_tugas: int|float|string, nilai_uts: int|float|string, nilai_uas: int|float|string, nilai_akhir: float, keterangan: string}
     */
    private function withCalculatedGrade(array $validated): array
    {
        $nilaiAkhir = round(
            ((float) $validated['nilai_tugas'] * 0.3)
                + ((float) $validated['nilai_uts'] * 0.3)
                + ((float) $validated['nilai_uas'] * 0.4),
            2
        );
        $mapel = Mapel::findOrFail((int) $validated['mapel_id']);

        return [
            ...$validated,
            'nilai_akhir' => $nilaiAkhir,
            'keterangan' => $nilaiAkhir >= $mapel->kkm ? 'Lulus' : 'Tidak Lulus',
        ];
    }
}
