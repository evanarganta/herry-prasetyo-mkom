@extends('layouts.main')

@section('title', 'Edit Data Nilai')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Edit Data Nilai</h1>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('nilai.update', $nilai) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="siswa_id">Siswa</label>
                        <select id="siswa_id" name="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror" required>
                            <option value="">Pilih siswa</option>
                            @foreach ($siswas as $siswa)
                                <option value="{{ $siswa->id }}" @selected(old('siswa_id', $nilai->siswa_id) == $siswa->id)>
                                    {{ $siswa->nis }} - {{ $siswa->nama }} ({{ $siswa->kelas->nama_kelas }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="mapel_id">Mata Pelajaran</label>
                        <select id="mapel_id" name="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}" @selected(old('mapel_id', $nilai->mapel_id) == $mapel->id)>
                                    {{ $mapel->kode_mapel }} - {{ $mapel->nama_mapel }} (KKM {{ $mapel->kkm }})
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nilai_tugas">Nilai Tugas (30%)</label>
                        <input id="nilai_tugas" name="nilai_tugas" type="number" min="0" max="100" step="0.01" value="{{ old('nilai_tugas', $nilai->nilai_tugas) }}" class="form-control @error('nilai_tugas') is-invalid @enderror" required>
                        @error('nilai_tugas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nilai_uts">Nilai UTS (30%)</label>
                        <input id="nilai_uts" name="nilai_uts" type="number" min="0" max="100" step="0.01" value="{{ old('nilai_uts', $nilai->nilai_uts) }}" class="form-control @error('nilai_uts') is-invalid @enderror" required>
                        @error('nilai_uts') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label for="nilai_uas">Nilai UAS (40%)</label>
                        <input id="nilai_uas" name="nilai_uas" type="number" min="0" max="100" step="0.01" value="{{ old('nilai_uas', $nilai->nilai_uas) }}" class="form-control @error('nilai_uas') is-invalid @enderror" required>
                        @error('nilai_uas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui Nilai</button>
                    <a href="{{ route('nilai.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
