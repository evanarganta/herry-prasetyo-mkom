<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => true]);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('users', UserManagementController::class)
        ->only(['index', 'store', 'destroy'])
        ->middleware('can:manage-users');

    Route::resource('kelas', KelasController::class)->except(['show']);
    Route::resource('siswa', SiswaController::class);
    Route::resource('guru', GuruController::class)->except(['show']);
    Route::resource('mapel', MapelController::class)->except(['show']);
    Route::resource('nilai', NilaiController::class)->except(['show']);
});
