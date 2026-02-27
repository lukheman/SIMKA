<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\AnggotaManagement;
use App\Livewire\Admin\AnggotaForm;
use App\Livewire\Admin\JenisSimpananManagement;
use App\Livewire\Admin\JenisPinjamanManagement;
use App\Livewire\Admin\Profile;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Guest\LandingPage;
use App\Http\Controllers\Admin\LogoutController;

// Guest Routes
Route::get('/', LandingPage::class)->name('home');

// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/anggota', AnggotaManagement::class)->name('admin.anggota');
    Route::get('/anggota/create', AnggotaForm::class)->name('admin.anggota.create');
    Route::get('/anggota/{id}/edit', AnggotaForm::class)->name('admin.anggota.edit');
    Route::get('/jenis-simpanan', JenisSimpananManagement::class)->name('admin.jenis-simpanan');
    Route::get('/jenis-pinjaman', JenisPinjamanManagement::class)->name('admin.jenis-pinjaman');
    Route::get('/profile', Profile::class)->name('admin.profile');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});