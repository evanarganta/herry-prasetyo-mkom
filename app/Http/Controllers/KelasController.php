<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelas = Kelas::withCount('siswas')->orderBy('nama_kelas')->get();

        return view('kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        return view('kelas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:10'],
            'jurusan' => ['required', 'string', 'max:255'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela): View
    {
        return view('kelas.edit', compact('kela'));
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:10'],
            'jurusan' => ['required', 'string', 'max:255'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        if ($kela->siswas()->exists()) {
            return redirect()->route('kelas.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
        }

        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
