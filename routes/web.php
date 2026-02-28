<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\AnggotaManagement;
use App\Livewire\Admin\AnggotaForm;
use App\Livewire\Admin\JenisSimpananManagement;
use App\Livewire\Admin\JenisPinjamanManagement;
use App\Livewire\Admin\PengajuanPinjamanManagement;
use App\Livewire\Admin\TransaksiSimpananManagement;
use App\Livewire\Admin\Profile;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Guest\LandingPage;
use App\Livewire\Anggota\AnggotaDashboard;
use App\Livewire\Anggota\PengajuanPinjamanList;
use App\Livewire\Anggota\PengajuanPinjamanCreate;
use App\Livewire\Anggota\AnggotaProfile;
use App\Livewire\Anggota\SimpananList;
use App\Http\Controllers\Admin\LogoutController;

// Guest Routes
Route::get('/', LandingPage::class)->name('home');

// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

// Admin Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/anggota', AnggotaManagement::class)->name('admin.anggota');
    Route::get('/anggota/create', AnggotaForm::class)->name('admin.anggota.create');
    Route::get('/anggota/{id}/edit', AnggotaForm::class)->name('admin.anggota.edit');
    Route::get('/jenis-simpanan', JenisSimpananManagement::class)->name('admin.jenis-simpanan');
    Route::get('/jenis-pinjaman', JenisPinjamanManagement::class)->name('admin.jenis-pinjaman');
    Route::get('/pengajuan-pinjaman', PengajuanPinjamanManagement::class)->name('admin.pengajuan-pinjaman');
    Route::get('/transaksi-simpanan', TransaksiSimpananManagement::class)->name('admin.transaksi-simpanan');
    Route::get('/profile', Profile::class)->name('admin.profile');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});

// Anggota Routes
Route::prefix('anggota')->middleware('auth:anggota')->group(function () {
    Route::get('/dashboard', AnggotaDashboard::class)->name('anggota.dashboard');
    Route::get('/pengajuan-pinjaman', PengajuanPinjamanList::class)->name('anggota.pengajuan-pinjaman');
    Route::get('/pengajuan-pinjaman/create', PengajuanPinjamanCreate::class)->name('anggota.pengajuan-pinjaman.create');
    Route::get('/simpanan', SimpananList::class)->name('anggota.simpanan');
    Route::get('/profile', AnggotaProfile::class)->name('anggota.profile');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('anggota.logout');
});