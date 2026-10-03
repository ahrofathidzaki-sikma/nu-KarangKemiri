<?php
use App\Http\Controllers\Admin;
use App\Http\Controllers\{AgendaPublikController, BeritaPublikController, HomeController, KtaPublikController};
use Illuminate\Support\Facades\Route;

// ===== PUBLIC =====
Route::get('/', [HomeController::class, 'beranda'])->name('beranda');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/struktur', [HomeController::class, 'struktur'])->name('struktur');
Route::get('/dokumentasi', [HomeController::class, 'dokumentasi'])->name('dokumentasi');
Route::get('/berita', [BeritaPublikController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaPublikController::class, 'show'])->name('berita.show');
Route::get('/agenda', [AgendaPublikController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{agenda}', [AgendaPublikController::class, 'show'])->whereNumber('agenda')->name('agenda.show');

// KTA publik — anggota bisa daftar & cetak sendiri
Route::get('/kta', [KtaPublikController::class, 'index'])->name('kta.index');
Route::get('/kta/daftar', [KtaPublikController::class, 'daftarForm'])->name('kta.daftar');
Route::post('/kta/daftar', [KtaPublikController::class, 'daftarStore'])->name('kta.daftar.store');
Route::post('/kta/cek', [KtaPublikController::class, 'cek'])->name('kta.cek');
Route::get('/kta/hasil/{nomor}', [KtaPublikController::class, 'hasil'])->name('kta.hasil');
Route::get('/kta/cetak/{nomor}', [KtaPublikController::class, 'cetak'])->name('kta.cetak');

// ===== LOGIN ADMIN =====

Route::get('/admin/login', [Admin\AuthController::class, 'form'])->name('login');
Route::post('/admin/login', [Admin\AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');

// ===== ADMIN (wajib login) =====
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

    Route::resource('agendas', Admin\AgendaController::class)->except('show')->parameters(['agendas' => 'agenda']);
    Route::resource('berita', Admin\BeritaController::class)->except('show')->parameters(['berita' => 'berita']);
    Route::resource('anggota', Admin\StrukturController::class)->except('show')->parameters(['anggota' => 'pengurus']);
    Route::resource('dokumentasi', Admin\DokumentasiController::class)->except('show')->parameters(['dokumentasi' => 'dokumentasi']);

    // Anggota & KTA
    Route::get('kta/unduh', [Admin\KtaController::class, 'unduh'])->name('kta.unduh');
    Route::get('kta/cetak-massal', [Admin\KtaController::class, 'cetakMassal'])->name('kta.cetak-massal');
    Route::get('kta/{kta}/cetak', [Admin\KtaController::class, 'cetak'])->name('kta.cetak');
    Route::resource('kta', Admin\KtaController::class)->except('show')->parameters(['kta' => 'kta']);

    Route::get('pengaturan', [Admin\PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('pengaturan', [Admin\PengaturanController::class, 'update'])->name('pengaturan.update');
});

