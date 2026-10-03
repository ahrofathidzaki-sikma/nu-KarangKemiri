<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Agenda, Anggota, Berita, Dokumentasi, StrukturOrganisasi};

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalAgenda' => Agenda::count(),
            'totalBerita' => Berita::count(),
            'totalPengurus' => StrukturOrganisasi::count(),
            'totalAnggota' => Anggota::count(),
            'totalAnggotaAktif' => Anggota::aktif()->count(),
            'totalDokumentasi' => Dokumentasi::count(),
            'agendaAkanDatang' => Agenda::akanDatang()->take(5)->get(),
            'beritaTerbaru' => Berita::orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}

