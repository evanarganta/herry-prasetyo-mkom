@extends('layouts.main')

@section('title', 'Hasil Pencarian')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Hasil Pencarian</h1>

        @if ($query === '')
            <div class="alert alert-info">Masukkan kata kunci untuk mencari data akademik.</div>
        @elseif ($results->isEmpty())
            <div class="alert alert-warning">Tidak ada data yang cocok dengan "{{ $query }}".</div>
        @else
            <p class="text-muted">Ditemukan {{ $resultCount }} hasil untuk "{{ $query }}".</p>

            <div class="card shadow mb-4">
                <div class="list-group list-group-flush">
                    @foreach ($results as $result)
                        <a href="{{ $result['url'] }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $result['title'] }}</h5>
                                <span class="badge badge-primary">{{ $result['type'] }}</span>
                            </div>
                            <small class="text-muted">{{ $result['details'] }}</small>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
