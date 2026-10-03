<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Agenda, Dokumentasi};
use App\Support\Upload;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    private function aturan(bool $wajibGambar): array
    {
        return [
            'agenda_id' => ['nullable', 'integer', 'exists:agendas,id'],
            'judul' => ['required', 'string', 'max:200'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'gambar' => [$wajibGambar ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    public function index()
    {
        return view('admin.dokumentasi.index', [
            'dokumentasis' => Dokumentasi::with('agenda')->latest()->paginate(12),
        ]);
    }

    public function create()
    {
        return view('admin.dokumentasi.form', [
            'dokumentasi' => new Dokumentasi(['agenda_id' => request('agenda')]),
            'agendas' => Agenda::orderByDesc('tanggal')->get(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate($this->aturan(true));
        $data['gambar'] = Upload::simpan($r->file('gambar'), 'dokumentasi');
        Dokumentasi::create($data);
        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function edit(Dokumentasi $dokumentasi)
    {
        return view('admin.dokumentasi.form', [
            'dokumentasi' => $dokumentasi,
            'agendas' => Agenda::orderByDesc('tanggal')->get(),
        ]);
    }

    public function update(Request $r, Dokumentasi $dokumentasi)
    {
        $data = $r->validate($this->aturan(false));
        $data['gambar'] = Upload::simpan($r->file('gambar'), 'dokumentasi', $dokumentasi->gambar);
        $dokumentasi->update($data);
        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        Upload::hapus($dokumentasi->gambar);
        $dokumentasi->delete();
        return redirect()->route('admin.dokumentasi.index')->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
