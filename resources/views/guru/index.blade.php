@extends('layouts.main')

@section('title', 'Data Guru')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Guru</h1>
            <a href="{{ route('guru.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm mr-1"></i> Tambah Guru
            </a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr><th>NIP</th><th>Nama</th><th>Jenis Kelamin</th><th>Email</th><th>No. HP</th><th>Alamat</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($gurus as $guru)
                                <tr>
                                    <td>{{ $guru->nip }}</td>
                                    <td>{{ $guru->nama }}</td>
                                    <td>{{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>{{ $guru->email ?: '-' }}</td>
                                    <td>{{ $guru->no_hp ?: '-' }}</td>
                                    <td>{{ $guru->alamat ?: '-' }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('guru.edit', $guru) }}" class="btn btn-warning btn-sm" aria-label="Edit guru">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('guru.destroy', $guru) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus guru">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Belum ada data guru.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
