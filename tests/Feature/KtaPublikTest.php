<?php

namespace Tests\Feature;

use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KtaPublikTest extends TestCase
{
    use RefreshDatabase;

    private function anggota(array $o = []): Anggota
    {
        return Anggota::create(array_merge([
            'nomor_anggota' => 'NU-2026-0001', 'nama' => 'Ahmad Fulan', 'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1965-08-17', 'no_wa' => '081234567890', 'organisasi' => 'nu', 'status' => 'aktif',
        ], $o));
    }

    private function dob(): array
    {
        return ['tgl' => 17, 'bln' => 8, 'thn' => 1965];
    }

    public function test_hasil_tidak_bisa_dibuka_tanpa_verifikasi(): void
    {
        $this->anggota();
        $this->get('/kta/hasil/NU-2026-0001')->assertRedirect(route('kta.index'));
        $this->get('/kta/cetak/NU-2026-0001')->assertRedirect(route('kta.index'));
        $this->get('/kta/hasil/NU-2026-9999')->assertRedirect(route('kta.index')); // sama: tidak bocor ada/tidaknya
    }

    public function test_cek_dengan_nomor_dan_tanggal_lahir(): void
    {
        $this->anggota();
        $this->post('/kta/cek', ['kunci' => 'nu 2026 1'] + $this->dob())->assertRedirect(route('kta.hasil', 'NU-2026-0001'));
        $this->get('/kta/hasil/NU-2026-0001')->assertOk()->assertSee('NU-2026-0001');
    }

    public function test_cek_gagal_jika_tanggal_lahir_salah(): void
    {
        $this->anggota();
        $this->post('/kta/cek', ['kunci' => 'NU-2026-0001', 'tgl' => 1, 'bln' => 1, 'thn' => 1990])->assertSessionHasErrors('kunci');
        $this->get('/kta/hasil/NU-2026-0001')->assertRedirect(route('kta.index'));
    }

    public function test_lupa_nomor_dengan_nama_dan_tanggal_lahir(): void
    {
        $this->anggota();
        $this->post('/kta/lupa', ['nama' => 'fulan ahmad'] + $this->dob())->assertRedirect(route('kta.hasil', 'NU-2026-0001'));
        $this->get('/kta/hasil/NU-2026-0001')->assertOk();
    }

    public function test_lupa_nomor_dengan_nama_dan_wa(): void
    {
        $this->anggota(['tanggal_lahir' => null]);
        $this->post('/kta/lupa', ['nama' => 'Ahmad Fulan', 'no_wa' => '+62 812-3456-7890'])->assertRedirect(route('kta.hasil', 'NU-2026-0001'));
    }

    public function test_lupa_nomor_tidak_ketemu_atau_tanpa_bukti(): void
    {
        $this->anggota();
        $this->post('/kta/lupa', ['nama' => 'Orang Lain'] + $this->dob())->assertSessionHasErrors('nama');
        $this->post('/kta/lupa', ['nama' => 'Ahmad Fulan'])->assertSessionHasErrors('tanggal_lahir');
    }

    public function test_lupa_nomor_beberapa_kecocokan_menampilkan_pilihan(): void
    {
        $this->anggota();
        $this->anggota(['nomor_anggota' => 'NU-2026-0002', 'nama' => 'Ahmad Fulan Rahman', 'no_wa' => '085555000111']);
        $this->post('/kta/lupa', ['nama' => 'Ahmad Fulan'] + $this->dob())->assertRedirect(route('kta.lupa.pilih'));
        $this->get('/kta/lupa/pilih')->assertOk()->assertSee('Ahmad Fulan Rahman');
        $this->post('/kta/lupa/pilih', ['id' => 2])->assertRedirect(route('kta.hasil', 'NU-2026-0002'));
    }

    public function test_daftar_membuat_anggota_dan_langsung_bisa_dilihat(): void
    {
        $this->post('/kta/daftar', [
            'nama' => 'Siti Aminah', 'jenis_kelamin' => 'P', 'organisasi' => 'ippnu',
            'tgl' => 2, 'bln' => 3, 'thn' => 2005, 'no_wa' => '0812 0000 1111',
        ])->assertRedirect();
        $a = Anggota::firstOrFail();
        $this->assertSame('2005-03-02', $a->tanggal_lahir->toDateString());
        $this->get(route('kta.hasil', $a->nomor_anggota))->assertOk()->assertSee('Siti Aminah');
    }

    public function test_daftar_ganda_tidak_membuat_data_baru(): void
    {
        $this->anggota();
        $this->post('/kta/daftar', ['nama' => 'AHMAD  fulan', 'jenis_kelamin' => 'L', 'organisasi' => 'nu'] + $this->dob())
            ->assertRedirect(route('kta.hasil', 'NU-2026-0001'));
        $this->assertSame(1, Anggota::count());
    }

    public function test_organisasi_harus_sesuai_jenis_kelamin(): void
    {
        $this->post('/kta/daftar', ['nama' => 'Budi', 'jenis_kelamin' => 'L', 'organisasi' => 'ippnu'] + $this->dob())->assertSessionHasErrors('organisasi');
        $this->post('/kta/daftar', ['nama' => 'Sari', 'jenis_kelamin' => 'P', 'organisasi' => 'ipnu'] + $this->dob())->assertSessionHasErrors('organisasi');
        $this->assertSame(0, Anggota::count());
    }

    public function test_tanggal_lahir_tidak_valid_ditolak(): void
    {
        $this->post('/kta/daftar', ['nama' => 'Budi Santoso', 'jenis_kelamin' => 'L', 'organisasi' => 'nu', 'tgl' => 31, 'bln' => 2, 'thn' => 1980])
            ->assertSessionHasErrors('tanggal_lahir');
    }
}
