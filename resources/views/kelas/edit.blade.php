@extends('layouts.main')

@section('title', 'Edit Data Kelas')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Data Kelas</h1>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('kelas.update', $kela) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_kelas">Nama Kelas</label>
                        <input id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" class="form-control @error('nama_kelas') is-invalid @enderror" required>
                        @error('nama_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tingkat">Tingkat</label>
                        <input id="tingkat" name="tingkat" value="{{ old('tingkat', $kela->tingkat) }}" class="form-control @error('tingkat') is-invalid @enderror" required>
                        @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jurusan">Jurusan</label>
                        <input id="jurusan" name="jurusan" value="{{ old('jurusan', $kela->jurusan) }}" class="form-control @error('jurusan') is-invalid @enderror" required>
                        @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="tahun_ajaran">Tahun Ajaran</label>
                        <input id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $kela->tahun_ajaran) }}" class="form-control @error('tahun_ajaran') is-invalid @enderror" required>
                        @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
