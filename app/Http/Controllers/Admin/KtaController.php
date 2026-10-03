<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Pengaturan;
use App\Support\Upload;
use Illuminate\Http\Request;

class KtaController extends Controller
{
    private function aturan(?Anggota $anggota = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'no_wa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'organisasi' => ['required', 'in:nu,ipnu,ippnu'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'tanggal_bergabung' => ['nullable', 'date'],
            'tanggal_undur' => ['nullable', 'date'],
            'alasan_undur' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function index(Request $r)
    {
        $q = Anggota::query()->orderByDesc('id');

        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }
        if ($r->filled('organisasi')) {
            $q->where('organisasi', $r->organisasi);
        }
        if ($r->filled('q')) {
            $s = $r->q;
            $q->where(function ($w) use ($s) {
                $w->where('nama', 'like', "%{$s}%")
                    ->orWhere('nomor_anggota', 'like', "%{$s}%")
                    ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        return view('admin.kta.index', [
            'anggotas' => $q->paginate(20)->withQueryString(),
            'totalAktif' => Anggota::aktif()->count(),
            'totalNonaktif' => Anggota::nonaktif()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.kta.form', ['anggota' => new Anggota(['status' => 'aktif', 'organisasi' => 'nu', 'tanggal_bergabung' => now()->toDateString()])]);
    }

    public function store(Request $r)
    {
        $data = $r->validate($this->aturan());
        $data['nomor_anggota'] = Anggota::generateNomor();
        $data['foto'] = Upload::simpan($r->file('foto'), 'anggota');

        if (($data['status'] ?? '') === 'aktif') {
            $data['tanggal_undur'] = null;
            $data['alasan_undur'] = null;
        }

        Anggota::create($data);
        return redirect()->route('admin.kta.index')->with('success', 'Anggota berhasil ditambahkan. Nomor: ' . $data['nomor_anggota']);
    }

    public function edit(Anggota $kta)
    {
        return view('admin.kta.form', ['anggota' => $kta]);
    }

    public function update(Request $r, Anggota $kta)
    {
        $data = $r->validate($this->aturan($kta));
        $data['foto'] = Upload::simpan($r->file('foto'), 'anggota', $kta->foto);

        if ($r->boolean('hapus_foto') && !$r->hasFile('foto')) {
            Upload::hapus($kta->foto);
            $data['foto'] = null;
        }

        if (($data['status'] ?? '') === 'aktif') {
            $data['tanggal_undur'] = null;
            $data['alasan_undur'] = null;
        } elseif (($data['status'] ?? '') === 'nonaktif' && empty($data['tanggal_undur'])) {
            $data['tanggal_undur'] = now()->toDateString();
        }

        $kta->update($data);
        return redirect()->route('admin.kta.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $kta)
    {
        Upload::hapus($kta->foto);
        $kta->delete();
        return redirect()->route('admin.kta.index')->with('success', 'Anggota berhasil dihapus.');
    }

    /** Cetak / tampil KTA satu anggota */
    public function cetak(Anggota $kta)
    {
        return view('admin.kta.cetak', [
            'anggota' => $kta,
            'pengaturan' => Pengaturan::current(),
        ]);
    }

    /** Cetak banyak KTA (semua aktif atau terpilih) */
    public function cetakMassal(Request $r)
    {
        $ids = $r->input('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(explode(',', $ids));
        }

        $q = Anggota::query()->orderBy('nama');
        if (!empty($ids)) {
            $q->whereIn('id', $ids);
        } else {
            $q->aktif();
        }

        $anggotas = $q->get();
        if ($anggotas->isEmpty()) {
            return redirect()->route('admin.kta.index')->with('error', 'Tidak ada anggota untuk dicetak.');
        }

        return view('admin.kta.cetak-massal', [
            'anggotas' => $anggotas,
            'pengaturan' => Pengaturan::current(),
        ]);
    }

    /** Unduh daftar anggota (CSV) */
    public function unduh(Request $r)
    {
        $q = Anggota::query()->orderBy('nama');
        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }

        $rows = $q->get();
        $filename = 'daftar-anggota-nu-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($out, [
                'No. Anggota', 'Nama', 'NIK', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
                'Alamat', 'No. WA', 'Organisasi', 'Status', 'Tgl Bergabung', 'Tgl Undur', 'Alasan Undur', 'Keterangan',
            ]);
            foreach ($rows as $a) {
                fputcsv($out, [
                    $a->nomor_anggota,
                    $a->nama,
                    $a->nik,
                    $a->jenis_kelamin_label,
                    $a->tempat_lahir,
                    $a->tanggal_lahir?->format('Y-m-d'),
                    $a->alamat,
                    $a->no_wa,
                    $a->organisasi_label,
                    $a->status_label,
                    $a->tanggal_bergabung?->format('Y-m-d'),
                    $a->tanggal_undur?->format('Y-m-d'),
                    $a->alasan_undur,
                    $a->keterangan,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
