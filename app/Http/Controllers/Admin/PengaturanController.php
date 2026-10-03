<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Support\Upload;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function edit()
    {
        return view('admin.pengaturan', ['p' => Pengaturan::current()]);
    }

    public function update(Request $r)
    {
        $data = $r->validate([
            'nama_organisasi' => ['required', 'string', 'max:150'],
            'nama_desa' => ['required', 'string', 'max:200'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ]);

        $p = Pengaturan::first() ?? new Pengaturan();
        $data['logo'] = Upload::simpan($r->file('logo'), 'logo', $p->logo);
        $p->fill($data)->save();

        return redirect()->route('admin.pengaturan.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
