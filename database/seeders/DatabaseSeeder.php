<?php
namespace Database\Seeders;

use App\Models\{Agenda, Berita, Dokumentasi, Pengaturan, StrukturOrganisasi, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@nudesa.test'], [
            'name' => 'Admin Ranting NU',
            'password' => Hash::make('Admin12345'),
        ]);

        Pengaturan::updateOrCreate(['id' => 1], [
            'nama_organisasi' => 'Ranting NU',
            'nama_desa' => 'Desa Karangkemiri',   // DUMMY - ganti lewat Admin > Pengaturan
            'kabupaten' => 'Kabupaten Banyumas',
            'alamat' => 'Jl. Raya Karangkemiri No. 1, Banyumas, Jawa Tengah',
            'deskripsi' => 'Pusat informasi kegiatan, warta, dan silaturahmi warga Nahdlatul Ulama tingkat desa. Menjaga tradisi Ahlussunnah wal Jamaah an-Nahdliyah di tengah masyarakat.',
            'telepon' => '0281-000000',
            'email' => 'ranting@nudesa.test',
            'instagram' => 'https://instagram.com/',
            'facebook' => 'https://facebook.com/',
            'youtube' => 'https://youtube.com/',
        ]);

        // Struktur (DUMMY) - urutan tampil mengikuti urutan input
        $pengurus = [
            ['KH. Ahmad Fulan', 'Rais Syuriyah', '2025-2030', 'Pengasuh pengajian rutin Jumat.'],
            ['H. Muhammad Fulan', 'Katib Syuriyah', '2025-2030', null],
            ['Drs. H. Ahmad Contoh', 'Ketua Tanfidziyah', '2025-2030', 'Koordinator seluruh program ranting.'],
            ['Siti Aminah, S.Pd.', 'Sekretaris', '2025-2030', 'Administrasi dan surat-menyurat.'],
            ['Budi Santoso', 'Bendahara', '2025-2030', 'Pengelola kas dan infak.'],
            ['Ustadz Rohmat', 'Ketua Lembaga Dakwah', '2025-2030', 'Jadwal ceramah dan kajian.'],
        ];
        foreach ($pengurus as [$nama, $jabatan, $periode, $ket]) {
            StrukturOrganisasi::updateOrCreate(['nama' => $nama], compact('jabatan', 'periode') + ['keterangan' => $ket]);
        }

        $agendas = [
            ['Pengajian Rutin Malam Jumat', now()->addDays(5), '19:30', 'Masjid Jami\' Al-Ikhlas', 'Pembacaan Yasin, tahlil, dan kajian kitab bersama warga.', 'Akan Datang'],
            ['Peringatan Maulid Nabi Muhammad SAW', now()->addDays(21), '08:00', 'Halaman Masjid Desa', 'Pembacaan maulid, ceramah agama, dan santunan anak yatim.', 'Akan Datang'],
            ['Kerja Bakti Bersih Musala', now()->subDays(10), '06:30', 'Musala Dusun 2', 'Gotong royong membersihkan dan mengecat musala.', 'Selesai'],
            ['Khataman Al-Quran Bersama', now()->subDays(30), '13:00', 'Aula Ranting NU', 'Khataman bersama jamaah ibu-ibu dan bapak-bapak.', 'Selesai'],
        ];
        foreach ($agendas as [$judul, $tgl, $waktu, $lokasi, $desk, $status]) {
            $a = Agenda::firstOrNew(['judul' => $judul]);
            if (!$a->exists) { $a->slug = Agenda::buatSlug($judul); }
            $a->fill(['tanggal' => $tgl, 'waktu' => $waktu, 'lokasi' => $lokasi, 'deskripsi' => $desk, 'status' => $status])->save();
        }

        $berita = [
            ['Ranting NU Gelar Pengajian Akbar Sambut Maulid', 'Pengurus Ranting NU menggelar pengajian akbar untuk menyambut Maulid Nabi. Ratusan warga hadir memenuhi halaman masjid dan mengikuti rangkaian acara hingga selesai.'],
            ['Warga Gotong Royong Bersihkan Musala', 'Puluhan warga dari berbagai dusun bahu-membahu membersihkan dan mengecat musala. Kegiatan ini menjadi wujud nyata semangat kebersamaan warga nahdliyin.'],
            ['Pelatihan Pengurusan Jenazah untuk Remaja Masjid', 'Lembaga dakwah ranting mengadakan pelatihan pengurusan jenazah bagi remaja masjid agar generasi muda siap berkhidmat kepada masyarakat.'],
            ['Santunan Anak Yatim Bulan Muharram', 'Ranting NU menyalurkan santunan kepada anak yatim dan dhuafa pada bulan Muharram, hasil infak dan sedekah warga.'],
        ];
        foreach ($berita as $i => [$judul, $isi]) {
            $b = Berita::firstOrNew(['judul' => $judul]);
            if (!$b->exists) { $b->slug = Berita::buatSlug($judul); }
            $b->fill([
                'tanggal' => now()->subDays($i * 4 + 1), 'penulis' => 'Admin Ranting NU',
                'isi' => $isi . "\n\nSemoga kegiatan seperti ini terus berlanjut dan membawa manfaat bagi seluruh warga.",
            ])->save();
        }

        $kerja = Agenda::where('judul', 'Kerja Bakti Bersih Musala')->first();
        $khatam = Agenda::where('judul', 'Khataman Al-Quran Bersama')->first();
        $dok = [
            [$kerja?->id, 'Warga membersihkan halaman musala'],
            [$kerja?->id, 'Pengecatan dinding musala'],
            [$khatam?->id, 'Jamaah ibu-ibu khataman'],
            [$khatam?->id, 'Doa bersama penutup khataman'],
            [null, 'Foto bersama pengurus ranting'],
            [null, 'Suasana pengajian rutin'],
        ];
        foreach ($dok as [$agendaId, $judul]) {
            Dokumentasi::updateOrCreate(['judul' => $judul], ['agenda_id' => $agendaId, 'keterangan' => 'Dokumentasi dummy - ganti lewat Admin.']);
        }
    }
}
