<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Support\Upload;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    private function aturan(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'tanggal' => ['required', 'date'],
            'penulis' => ['required', 'string', 'max:100'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function index(Request $r)
    {
        $q = Berita::query();
        if ($r->filled('q')) {
            $q->where('judul', 'like', '%' . $r->q . '%');
        }
        return view('admin.berita.index', ['beritas' => $q->orderByDesc('tanggal')->orderByDesc('id')->paginate(10)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.berita.form', ['berita' => new Berita(['tanggal' => now(), 'penulis' => auth()->user()->name])]);
    }

    public function store(Request $r)
    {
        $data = $r->validate($this->aturan());
        $data['slug'] = Berita::buatSlug($data['judul']);
        $data['gambar'] = Upload::simpan($r->file('gambar'), 'berita');
        Berita::create($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $berita)
    {
        return view('admin.berita.form', compact('berita'));
    }

    public function update(Request $r, Berita $berita)
    {
        $data = $r->validate($this->aturan());
        if ($data['judul'] !== $berita->judul) {
            $data['slug'] = Berita::buatSlug($data['judul'], $berita->id);
        }
        $data['gambar'] = Upload::simpan($r->file('gambar'), 'berita', $berita->gambar);
        if ($r->boolean('hapus_gambar') && !$r->hasFile('gambar')) {
            Upload::hapus($berita->gambar);
            $data['gambar'] = null;
        }
        $berita->update($data);
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        Upload::hapus($berita->gambar);
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
