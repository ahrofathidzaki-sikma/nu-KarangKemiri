<?php
namespace App\Http\Controllers;

use App\Models\{Agenda, Berita, Dokumentasi, StrukturOrganisasi};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function beranda()
    {
        return view('public.beranda', [
            'agendas' => Agenda::akanDatang()->take(3)->get(),
            'beritas' => Berita::orderByDesc('tanggal')->orderByDesc('id')->take(3)->get(),
            'dokumentasis' => Dokumentasi::with('agenda')->latest()->take(6)->get(),
            'jumlah' => [
                'agenda' => Agenda::count(),
                'berita' => Berita::count(),
                'pengurus' => StrukturOrganisasi::count(),
            ],
        ]);
    }

    public function tentang()
    {
        return view('public.tentang');
    }

    public function struktur()
    {
        return view('public.struktur', [
            'ipnu' => StrukturOrganisasi::ipnu()->orderBy('id')->get(),
            'ippnu' => StrukturOrganisasi::ippnu()->orderBy('id')->get(),
        ]);
    }


    public function dokumentasi(Request $r)
    {
        $q = Dokumentasi::with('agenda')->latest();
        if ($r->filled('agenda')) {
            $q->where('agenda_id', $r->integer('agenda'));
        }
        return view('public.dokumentasi', [
            'dokumentasis' => $q->paginate(12)->withQueryString(),
            'agendas' => Agenda::whereHas('dokumentasis')->orderByDesc('tanggal')->get(),
        ]);
    }
}
