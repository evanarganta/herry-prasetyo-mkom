@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat email</label>
                        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-gray-800">Ubah Kata Sandi</h6>
                    <p class="text-muted">Kosongkan jika tidak ingin mengganti kata sandi.</p>

                    <div class="form-group">
                        <label for="current_password">Kata sandi saat ini</label>
                        <input id="current_password" name="current_password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            autocomplete="current-password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Kata sandi baru</label>
                        <input id="password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi kata sandi baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
                            autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
@endsection
