<?php
use App\Http\Controllers\Admin;
use App\Http\Controllers\{AgendaPublikController, BeritaPublikController, HomeController};
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

    Route::get('pengaturan', [Admin\PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('pengaturan', [Admin\PengaturanController::class, 'update'])->name('pengaturan.update');
});
