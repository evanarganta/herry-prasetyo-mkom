@extends('layouts.main')

@section('title', 'Edit Data Siswa')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Data Siswa</h1>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('siswa.update', $siswa) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input id="nis" name="nis" value="{{ old('nis', $siswa->nis) }}" class="form-control @error('nis') is-invalid @enderror" required>
                        @error('nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input id="nama" name="nama" value="{{ old('nama', $siswa->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="kelas_id">Kelas</label>
                        <select id="kelas_id" name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                            <option value="">Pilih kelas</option>
                            @foreach ($kelas as $item)
                                <option value="{{ $item->id }}" @selected(old('kelas_id', $siswa->kelas_id) == $item->id)>{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                        @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input id="no_hp" name="no_hp" value="{{ old('no_hp', $siswa->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror">
                        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $siswa->alamat) }}</textarea>
                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
