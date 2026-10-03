<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Pengaturan;
use App\Support\Upload;
use Illuminate\Http\Request;

class KtaPublikController extends Controller
{
    /** Halaman utama KTA: daftar + cek */
    public function index()
    {
        return view('public.kta', [
            'pengaturan' => Pengaturan::current(),
        ]);
    }

    /** Form daftar anggota (buat KTA) */
    public function daftarForm()
    {
        return view('public.kta-daftar');
    }

    /** Proses pendaftaran anggota */
    public function daftarStore(Request $r)
    {
        $data = $r->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'no_wa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'organisasi' => ['required', 'in:nu,ipnu,ippnu'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'organisasi.required' => 'Pilih organisasi.',
        ]);

        // Cegah duplikat NIK jika diisi
        if (!empty($data['nik'])) {
            $exists = Anggota::where('nik', $data['nik'])->where('status', 'aktif')->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['nik' => 'NIK ini sudah terdaftar sebagai anggota aktif.']);
            }
        }

        $data['nomor_anggota'] = Anggota::generateNomor();
        $data['status'] = 'aktif';
        $data['tanggal_bergabung'] = now()->toDateString();
        $data['foto'] = Upload::simpan($r->file('foto'), 'anggota');

        $anggota = Anggota::create($data);

        return redirect()
            ->route('kta.hasil', $anggota->nomor_anggota)
            ->with('success', 'Pendaftaran berhasil! Simpan nomor anggota Anda.');
    }

    /** Cek KTA berdasarkan nomor anggota atau NIK */
    public function cek(Request $r)
    {
        $r->validate([
            'kunci' => ['required', 'string', 'max:50'],
        ], [
            'kunci.required' => 'Masukkan nomor anggota atau NIK.',
        ]);

        $kunci = trim($r->kunci);

        $anggota = Anggota::where('nomor_anggota', $kunci)
            ->orWhere('nik', $kunci)
            ->first();

        if (!$anggota) {
            return back()->withInput()->withErrors(['kunci' => 'Data tidak ditemukan. Periksa nomor anggota atau NIK.']);
        }

        return redirect()->route('kta.hasil', $anggota->nomor_anggota);
    }

    /** Hasil: tampil info + tombol cetak KTA */
    public function hasil(string $nomor)
    {
        $anggota = Anggota::where('nomor_anggota', $nomor)->firstOrFail();

        return view('public.kta-hasil', [
            'anggota' => $anggota,
            'pengaturan' => Pengaturan::current(),
        ]);
    }

    /** Cetak KTA (publik, hanya anggota aktif) */
    public function cetak(string $nomor)
    {
        $anggota = Anggota::where('nomor_anggota', $nomor)->firstOrFail();

        if ($anggota->status !== 'aktif') {
            return redirect()->route('kta.hasil', $nomor)
                ->with('error', 'KTA tidak dapat dicetak karena status keanggotaan nonaktif/undur.');
        }

        return view('admin.kta.cetak', [
            'anggota' => $anggota,
            'pengaturan' => Pengaturan::current(),
            'publik' => true,
        ]);
    }
}
