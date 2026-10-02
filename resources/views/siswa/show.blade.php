@extends('layouts.main')

@section('title', 'Detail Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Siswa</h1>
            <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-warning btn-sm">Edit Siswa</a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">Profil {{ $siswa->nama }}</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">NIS</dt><dd class="col-sm-9">{{ $siswa->nis }}</dd>
                    <dt class="col-sm-3">Kelas</dt><dd class="col-sm-9">{{ $siswa->kelas->nama_kelas }}</dd>
                    <dt class="col-sm-3">Jenis Kelamin</dt><dd class="col-sm-9">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    <dt class="col-sm-3">No. HP</dt><dd class="col-sm-9">{{ $siswa->no_hp ?: '-' }}</dd>
                    <dt class="col-sm-3">Alamat</dt><dd class="col-sm-9">{{ $siswa->alamat ?: '-' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header font-weight-bold text-primary">Riwayat Nilai</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr><th>Mata Pelajaran</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Nilai Akhir</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($siswa->nilais as $nilai)
                                <tr>
                                    <td>{{ $nilai->mapel->nama_mapel }}</td>
                                    <td>{{ $nilai->nilai_tugas }}</td>
                                    <td>{{ $nilai->nilai_uts }}</td>
                                    <td>{{ $nilai->nilai_uas }}</td>
                                    <td>{{ $nilai->nilai_akhir }}</td>
                                    <td>{{ $nilai->keterangan }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada nilai untuk siswa ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ route('siswa.index') }}" class="btn btn-secondary mb-4">Kembali</a>
    </div>
@endsection
