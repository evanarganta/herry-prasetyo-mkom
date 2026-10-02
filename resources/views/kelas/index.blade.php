@extends('layouts.main')

@section('title', 'Data Kelas')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Kelas</h1>
            <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm mr-1"></i> Tambah Kelas
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr><th>Nama Kelas</th><th>Tingkat</th><th>Jurusan</th><th>Tahun Ajaran</th><th>Jumlah Siswa</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($kelas as $item)
                                <tr>
                                    <td>{{ $item->nama_kelas }}</td>
                                    <td>{{ $item->tingkat }}</td>
                                    <td>{{ $item->jurusan }}</td>
                                    <td>{{ $item->tahun_ajaran }}</td>
                                    <td>{{ $item->siswas_count }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('kelas.edit', $item) }}" class="btn btn-warning btn-sm" aria-label="Edit kelas">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('kelas.destroy', $item) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus kelas">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada data kelas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
