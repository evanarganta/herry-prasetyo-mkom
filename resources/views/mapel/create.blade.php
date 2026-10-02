@extends('layouts.main')

@section('title', 'Tambah Data Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Tambah Mata Pelajaran</h1>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('mapel.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="kode_mapel">Kode Mapel</label>
                        <input id="kode_mapel" name="kode_mapel" value="{{ old('kode_mapel') }}" class="form-control @error('kode_mapel') is-invalid @enderror" required>
                        @error('kode_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama_mapel">Nama Mapel</label>
                        <input id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel') }}" class="form-control @error('nama_mapel') is-invalid @enderror" required>
                        @error('nama_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kkm">Nilai KKM</label>
                        <input id="kkm" name="kkm" type="number" min="0" max="100" value="{{ old('kkm', 75) }}" class="form-control @error('kkm') is-invalid @enderror" required>
                        @error('kkm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('mapel.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
