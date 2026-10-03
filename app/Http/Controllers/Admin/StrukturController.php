<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Support\Upload;
use Illuminate\Http\Request;

class StrukturController extends Controller
{
    private function aturan(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150'],
            'periode' => ['nullable', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function index()
    {
        return view('admin.anggota.index', ['pengurus' => StrukturOrganisasi::orderBy('id')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.anggota.form', ['pengurus' => new StrukturOrganisasi()]);
    }

    public function store(Request $r)
    {
        $data = $r->validate($this->aturan());
        $data['foto'] = Upload::simpan($r->file('foto'), 'pengurus');
        StrukturOrganisasi::create($data);
        return redirect()->route('admin.anggota.index')->with('success', 'Pengurus berhasil ditambahkan.');
    }

    public function edit(StrukturOrganisasi $pengurus)
    {
        return view('admin.anggota.form', compact('pengurus'));
    }

    public function update(Request $r, StrukturOrganisasi $pengurus)
    {
        $data = $r->validate($this->aturan());
        $data['foto'] = Upload::simpan($r->file('foto'), 'pengurus', $pengurus->foto);
        if ($r->boolean('hapus_foto') && !$r->hasFile('foto')) {
            Upload::hapus($pengurus->foto);
            $data['foto'] = null;
        }
        $pengurus->update($data);
        return redirect()->route('admin.anggota.index')->with('success', 'Data pengurus berhasil diperbarui.');
    }

    public function destroy(StrukturOrganisasi $pengurus)
    {
        Upload::hapus($pengurus->foto);
        $pengurus->delete();
        return redirect()->route('admin.anggota.index')->with('success', 'Pengurus berhasil dihapus.');
    }
}
