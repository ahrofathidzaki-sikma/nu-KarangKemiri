<?php
namespace App\Providers;

use App\Models\Pengaturan;
use Carbon\Carbon;
use App\Models\Agenda;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        // Zona waktu WIB (Banyumas) agar status agenda tepat waktu.
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');

        // Sinkronkan status agenda otomatis (maksimal sekali per menit).
        if (!$this->app->runningInConsole()) {
            try {
                if (Schema::hasTable('agendas') && Cache::add('agenda-sinkron', 1, 60)) {
                    Agenda::sinkronStatus();
                }
            } catch (\Throwable $e) {
                // abaikan: jangan sampai halaman gagal tampil
            }
        }

        // Semua view menerima $pengaturan dari database (nama desa, kontak, dll).
        View::composer('*', function ($view) {
            static $p = null;
            if ($p === null) {
                try {
                    $p = Schema::hasTable('pengaturans') ? Pengaturan::current() : new Pengaturan();
                } catch (\Throwable $e) {
                    $p = new Pengaturan();
                }
            }
            $view->with('pengaturan', $p);
        });
    }
}
