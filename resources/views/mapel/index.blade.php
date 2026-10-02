@extends('layouts.main')

@section('title', 'Data Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Mata Pelajaran</h1>
            <a href="{{ route('mapel.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm mr-1"></i> Tambah Mata Pelajaran
            </a>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr><th>Kode Mapel</th><th>Nama Mapel</th><th>KKM</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($mapels as $mapel)
                                <tr>
                                    <td>{{ $mapel->kode_mapel }}</td>
                                    <td>{{ $mapel->nama_mapel }}</td>
                                    <td>{{ $mapel->kkm }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('mapel.edit', $mapel) }}" class="btn btn-warning btn-sm" aria-label="Edit mata pelajaran">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" aria-label="Hapus mata pelajaran">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Belum ada data mata pelajaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
