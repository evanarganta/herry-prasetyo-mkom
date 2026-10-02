@extends('layouts.main')

@section('title', 'Data Siswa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Siswa</h1>
            <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm mr-1"></i> Tambah Siswa
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                                <tr>
                                    <td>{{ $siswa->nis }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas->nama_kelas }}</td>
                                    <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $siswa->no_hp ?: '-' }}</td>
                                    <td>{{ $siswa->alamat ?: '-' }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('siswa.show', $siswa) }}" class="btn btn-info btn-sm" aria-label="Detail siswa">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('siswa.edit', $siswa) }}" class="btn btn-warning btn-sm" aria-label="Edit siswa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('siswa.destroy', $siswa) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus siswa ini beserta seluruh nilai akademiknya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus siswa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
