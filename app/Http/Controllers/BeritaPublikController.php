<?php
namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaPublikController extends Controller
{
    public function index()
    {
        return view('public.berita-index', [
            'beritas' => Berita::orderByDesc('tanggal')->orderByDesc('id')->paginate(9),
        ]);
    }

    public function show(string $slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();
        $lainnya = Berita::where('id', '!=', $berita->id)->orderByDesc('tanggal')->take(3)->get();
        return view('public.berita-show', compact('berita', 'lainnya'));
    }
}
