<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Support\Upload;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;

class KtaPublikController extends Controller
{
    /** Kunci sesi: daftar nomor anggota yang sudah "dibuka" pengunjung ini. */
    private const SESI = 'kta_akses';

    private const PESAN = [
        'kunci.required' => 'Masukkan nomor anggota atau NIK Anda.',
        'nama.required' => 'Nama lengkap wajib diisi.',
        'nama.min' => 'Nama terlalu pendek.',
        'jenis_kelamin.required' => 'Pilih jenis kelamin.',
        'organisasi.required' => 'Pilih organisasi.',
        'tanggal_lahir.required' => 'Isi tanggal, bulan, dan tahun lahir.',
        'tanggal_lahir.date_format' => 'Tanggal lahir tidak valid. Periksa tanggal, bulan, dan tahun (4 angka).',
        'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan.',
        'tanggal_lahir.after' => 'Tahun lahir tidak valid.',
        'no_wa.regex' => 'Nomor WA hanya boleh berisi angka. Contoh: 0812 3456 7890.',
        'nik.regex' => 'NIK harus 16 angka, tanpa spasi.',
        'foto.image' => 'Foto harus berupa gambar (JPG, PNG, atau WEBP).',
        'foto.max' => 'Ukuran foto maksimal 2 MB.',
    ];

    // ---------- akses sesi ----------

    private function beriAkses(Anggota $a): void
    {
        $daftar = session(self::SESI, []);
        $daftar[] = $a->nomor_anggota;
        session([self::SESI => array_values(array_unique($daftar))]);
    }

    private function punyaAkses(string $nomor): bool
    {
        return in_array($nomor, session(self::SESI, []), true);
    }

    /** Gabungkan isian tgl/bln/thn menjadi tanggal_lahir (Y-m-d) agar mudah diisi di HP. */
    private function gabungTanggal(Request $r): void
    {
        $tgl = trim((string) $r->input('tgl'));
        $bln = trim((string) $r->input('bln'));
        $thn = trim((string) $r->input('thn'));

        if ($tgl === '' && $bln === '' && $thn === '') {
            $r->merge(['tanggal_lahir' => null]);
            return;
        }

        $ok = ctype_digit($tgl) && ctype_digit($bln) && ctype_digit($thn)
            && strlen($thn) === 4 && checkdate((int) $bln, (int) $tgl, (int) $thn);

        $r->merge(['tanggal_lahir' => $ok ? sprintf('%04d-%02d-%02d', $thn, $bln, $tgl) : 'tidak-valid']);
    }

    private function tolakAkses()
    {
        return redirect()->route('kta.index')
            ->with('error', 'Untuk keamanan data, silakan masukkan nomor anggota dan tanggal lahir Anda terlebih dahulu.');
    }

    // ---------- halaman ----------

    public function index()
    {
        return view('public.kta');
    }

    public function daftarForm()
    {
        return view('public.kta-daftar');
    }

    public function daftarStore(Request $r)
    {
        $this->gabungTanggal($r);
        $r->merge(['nik' => preg_replace('/\s+/', '', (string) $r->input('nik'))]);

        $data = $r->validate([
            'nama' => ['required', 'string', 'min:3', 'max:150'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date_format:Y-m-d', 'after:1900-01-01', 'before:tomorrow'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'nik' => ['nullable', 'regex:/^\d{16}$/'],
            'no_wa' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'organisasi' => ['required', 'in:nu,ipnu,ippnu'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], self::PESAN);

        $data['nik'] = $data['nik'] ?: null;

        // IPNU khusus laki-laki, IPPNU khusus perempuan
        if (($data['jenis_kelamin'] === 'L' && $data['organisasi'] === 'ippnu')
            || ($data['jenis_kelamin'] === 'P' && $data['organisasi'] === 'ipnu')) {
            return back()->withInput()->withErrors([
                'organisasi' => 'IPNU khusus laki-laki dan IPPNU khusus perempuan. Pilih "Umum" atau organisasi yang sesuai.',
            ]);
        }

        // Sudah pernah mendaftar? (nama sama + tanggal lahir sama) => jangan buat data ganda
        // (dibandingkan di PHP agar tidak bergantung pada format penyimpanan tanggal di database)
        $namaBaru = Anggota::normalNama($data['nama']);
        $sudah = Anggota::whereNotNull('tanggal_lahir')->get()->first(
            fn (Anggota $a) => $a->tanggal_lahir->toDateString() === $data['tanggal_lahir']
                && Anggota::normalNama($a->nama) === $namaBaru
        );

        if ($sudah) {
            if ($sudah->status !== 'aktif') {
                return back()->withInput()->withErrors([
                    'nama' => 'Keanggotaan atas nama ini tercatat nonaktif/undur. Silakan hubungi pengurus untuk diaktifkan kembali.',
                ]);
            }
            $this->beriAkses($sudah);
            return redirect()->route('kta.hasil', $sudah->nomor_anggota)
                ->with('info', 'Anda sudah terdaftar sebelumnya, jadi tidak perlu mendaftar lagi. Berikut data dan nomor anggota Anda.');
        }

        if (!empty($data['nik']) && Anggota::where('nik', $data['nik'])->exists()) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK ini sudah terdaftar. Jika Anda lupa nomor anggota, gunakan menu "Lupa nomor anggota".',
            ]);
        }

        $data['status'] = 'aktif';
        $data['tanggal_bergabung'] = now()->toDateString();
        $data['foto'] = Upload::simpan($r->file('foto'), 'anggota');

        $anggota = null;
        for ($i = 0; $i < 3; $i++) {
            try {
                $data['nomor_anggota'] = Anggota::generateNomor();
                $anggota = Anggota::create($data);
                break;
            } catch (UniqueConstraintViolationException $e) {
                if ($i === 2) {
                    throw $e;
                }
            }
        }

        $this->beriAkses($anggota);

        return redirect()->route('kta.hasil', $anggota->nomor_anggota)
            ->with('success', 'Pendaftaran berhasil! Catat atau simpan nomor anggota Anda di bawah ini.');
    }

    /** Sudah ingat nomor/NIK: wajib disertai tanggal lahir sebagai kunci keamanan. */
    public function cek(Request $r)
    {
        $this->gabungTanggal($r);

        $data = $r->validate([
            'kunci' => ['required', 'string', 'max:50'],
            'tanggal_lahir' => ['required', 'date_format:Y-m-d', 'after:1900-01-01', 'before:tomorrow'],
        ], self::PESAN);

        $nomor = Anggota::rapikanNomor($data['kunci']);
        $nik = preg_replace('/\D+/', '', $data['kunci']);

        $anggota = Anggota::where('nomor_anggota', $nomor)
            ->when(strlen($nik) === 16, fn ($q) => $q->orWhere('nik', $nik))
            ->first();

        $cocok = $anggota && $anggota->tanggal_lahir
            && $anggota->tanggal_lahir->toDateString() === $data['tanggal_lahir'];

        if (!$cocok) {
            // Pesan sama untuk semua kemungkinan agar data orang lain tidak bisa ditebak.
            return back()->withInput()->withErrors([
                'kunci' => 'Nomor/NIK dan tanggal lahir tidak cocok dengan data kami. Periksa kembali, atau gunakan "Lupa nomor anggota".',
            ]);
        }

        $this->beriAkses($anggota);

        return redirect()->route('kta.hasil', $anggota->nomor_anggota);
    }

    // ---------- lupa nomor ----------

    public function lupaForm()
    {
        return view('public.kta-lupa');
    }

    public function lupaCari(Request $r)
    {
        $this->gabungTanggal($r);

        $data = $r->validate([
            'nama' => ['required', 'string', 'min:3', 'max:150'],
            'tanggal_lahir' => ['nullable', 'date_format:Y-m-d', 'after:1900-01-01', 'before:tomorrow'],
            'no_wa' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+\-\s]+$/'],
        ], self::PESAN);

        if (empty($data['tanggal_lahir']) && empty($data['no_wa'])) {
            return back()->withInput()->withErrors([
                'tanggal_lahir' => 'Isi tanggal lahir Anda, atau nomor WA yang dulu didaftarkan.',
            ]);
        }

        $cocok = Anggota::cariNama($data['nama'])->get()->filter(function (Anggota $a) use ($data) {
            if (!empty($data['tanggal_lahir']) && optional($a->tanggal_lahir)->toDateString() !== $data['tanggal_lahir']) {
                return false;
            }
            if (!empty($data['no_wa']) && Anggota::normalisasiWa($a->no_wa) !== Anggota::normalisasiWa($data['no_wa'])) {
                return false;
            }
            return true;
        })->values();

        if ($cocok->isEmpty()) {
            return back()->withInput()->withErrors([
                'nama' => 'Kami belum menemukan data yang cocok. Coba ketik nama sesuai yang didaftarkan, atau hubungi pengurus.',
            ]);
        }

        if ($cocok->count() === 1) {
            $this->beriAkses($cocok->first());
            return redirect()->route('kta.hasil', $cocok->first()->nomor_anggota)
                ->with('info', 'Data Anda ditemukan. Catat atau simpan nomor anggota di bawah ini agar tidak lupa lagi.');
        }

        session(['kta_pilihan' => $cocok->pluck('id')->all()]);

        return redirect()->route('kta.lupa.pilih');
    }

    public function pilih()
    {
        $ids = session('kta_pilihan');
        if (empty($ids)) {
            return redirect()->route('kta.lupa');
        }

        return view('public.kta-pilih', ['anggotas' => Anggota::whereIn('id', $ids)->get()]);
    }

    public function pilihStore(Request $r)
    {
        $r->validate(['id' => ['required', 'integer']]);

        $ids = session('kta_pilihan', []);
        if (!in_array((int) $r->input('id'), $ids, true)) {
            return redirect()->route('kta.lupa');
        }

        $anggota = Anggota::findOrFail((int) $r->input('id'));
        $this->beriAkses($anggota);
        session()->forget('kta_pilihan');

        return redirect()->route('kta.hasil', $anggota->nomor_anggota)
            ->with('info', 'Data Anda ditemukan. Catat atau simpan nomor anggota di bawah ini agar tidak lupa lagi.');
    }

    // ---------- hasil & cetak (hanya untuk yang sudah terverifikasi) ----------

    public function hasil(string $nomor)
    {
        $anggota = Anggota::where('nomor_anggota', $nomor)->first();

        if (!$anggota || !$this->punyaAkses($nomor)) {
            return $this->tolakAkses();
        }

        return view('public.kta-hasil', ['anggota' => $anggota]);
    }

    public function cetak(string $nomor)
    {
        $anggota = Anggota::where('nomor_anggota', $nomor)->first();

        if (!$anggota || !$this->punyaAkses($nomor)) {
            return $this->tolakAkses();
        }

        if ($anggota->status !== 'aktif') {
            return redirect()->route('kta.hasil', $nomor)
                ->with('error', 'KTA tidak dapat dicetak karena status keanggotaan nonaktif/undur.');
        }

        return view('admin.kta.cetak', ['anggota' => $anggota, 'publik' => true]);
    }
}
