<?php
namespace Tests\Feature;

use App\Models\{Agenda, Berita, Dokumentasi, Pengaturan, StrukturOrganisasi, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): static
    {
        return $this->actingAs(User::factory()->create());
    }

    public function test_tamu_tidak_bisa_masuk_admin(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/admin/agendas')->assertRedirect(route('login'));
    }

    public function test_crud_agenda_dengan_status_otomatis(): void
    {
        $this->admin();
        $depan = now()->addDays(3)->format('Y-m-d');
        $lalu = now()->subDays(3)->format('Y-m-d');
        $data = ['judul' => 'Pengajian Test', 'tanggal' => $depan, 'waktu' => '19:30', 'lokasi' => 'Masjid'];

        $this->post('/admin/agendas', $data)->assertRedirect(route('admin.agendas.index'));
        $this->assertDatabaseHas('agendas', ['judul' => 'Pengajian Test', 'status' => 'Akan Datang']);
        $this->get('/agenda')->assertSee('Pengajian Test');

        $a = Agenda::first();
        $this->put("/admin/agendas/{$a->id}", array_merge($data, ['judul' => 'Pengajian Baru', 'tanggal' => $lalu]));
        $this->assertDatabaseHas('agendas', ['id' => $a->id, 'judul' => 'Pengajian Baru', 'status' => 'Selesai']);

        $this->delete("/admin/agendas/{$a->id}");
        $this->assertDatabaseMissing('agendas', ['id' => $a->id]);
    }

    public function test_sinkron_status_memindahkan_agenda_yang_sudah_dimulai(): void
    {
        $a = Agenda::create(['judul' => 'Lewat', 'slug' => 'lewat', 'tanggal' => now()->subHour()->format('Y-m-d'),
            'waktu' => now()->subHour()->format('H:i'), 'lokasi' => 'X', 'status' => 'Akan Datang']);
        Agenda::sinkronStatus();
        $this->assertSame('Selesai', $a->fresh()->status);
    }

    public function test_crud_berita_dengan_gambar(): void
    {
        Storage::fake('public');
        $this->admin();
        $this->post('/admin/berita', [
            'judul' => 'Berita Uji', 'tanggal' => '2026-10-01', 'penulis' => 'Admin', 'isi' => 'Isi berita.',
            'gambar' => UploadedFile::fake()->image('a.jpg'),
        ])->assertRedirect(route('admin.berita.index'));

        $b = Berita::firstOrFail();
        Storage::disk('public')->assertExists($b->gambar);
        $this->get('/berita/' . $b->slug)->assertOk()->assertSee('Berita Uji');

        $lama = $b->gambar;
        $this->put("/admin/berita/{$b->id}", [
            'judul' => 'Berita Edit', 'tanggal' => '2026-10-01', 'penulis' => 'Admin', 'isi' => 'Baru',
            'gambar' => UploadedFile::fake()->image('b.jpg'),
        ]);
        Storage::disk('public')->assertMissing($lama);
        $this->assertDatabaseHas('beritas', ['id' => $b->id, 'judul' => 'Berita Edit']);

        $b->refresh();
        $this->delete("/admin/berita/{$b->id}");
        $this->assertDatabaseMissing('beritas', ['id' => $b->id]);
        Storage::disk('public')->assertMissing($b->gambar);
    }

    public function test_crud_struktur(): void
    {
        Storage::fake('public');
        $this->admin();
        $this->post('/admin/anggota', ['nama' => 'Fulan', 'jabatan' => 'Ketua', 'periode' => '2025-2030', 'foto' => UploadedFile::fake()->image('f.jpg')]);
        $p = StrukturOrganisasi::firstOrFail();
        $this->get('/struktur')->assertSee('Fulan');
        $this->put("/admin/anggota/{$p->id}", ['nama' => 'Fulan Edit', 'jabatan' => 'Sekretaris']);
        $this->assertDatabaseHas('struktur_organisasis', ['id' => $p->id, 'nama' => 'Fulan Edit', 'jabatan' => 'Sekretaris']);
        $this->delete("/admin/anggota/{$p->id}");
        $this->assertDatabaseMissing('struktur_organisasis', ['id' => $p->id]);
    }

    public function test_crud_dokumentasi_dan_relasi(): void
    {
        Storage::fake('public');
        $this->admin();
        $agenda = Agenda::create(['judul' => 'X', 'slug' => 'x', 'tanggal' => '2026-01-01', 'lokasi' => 'Y', 'status' => 'Selesai']);

        $this->post('/admin/dokumentasi', ['judul' => 'Foto 1', 'agenda_id' => $agenda->id, 'gambar' => UploadedFile::fake()->image('1.jpg')]);
        $d = Dokumentasi::firstOrFail();
        $this->assertTrue($agenda->dokumentasis->contains($d));

        $this->post('/admin/dokumentasi', ['judul' => 'Salah', 'agenda_id' => 9999, 'gambar' => UploadedFile::fake()->image('2.jpg')])->assertSessionHasErrors('agenda_id');

        $this->delete("/admin/agendas/{$agenda->id}");
        $this->assertDatabaseHas('dokumentasis', ['id' => $d->id, 'agenda_id' => null]);

        $this->delete("/admin/dokumentasi/{$d->id}");
        $this->assertDatabaseMissing('dokumentasis', ['id' => $d->id]);
    }

    public function test_pengaturan_mengubah_nama_desa_di_seluruh_website(): void
    {
        $this->admin();
        $this->put('/admin/pengaturan', ['nama_organisasi' => 'Ranting NU', 'nama_desa' => 'Desa Contoh', 'kabupaten' => 'Kabupaten Banyumas'])
            ->assertRedirect(route('admin.pengaturan.edit'));
        $this->assertDatabaseHas('pengaturans', ['nama_desa' => 'Desa Contoh']);
        $this->get('/')->assertSee('Desa Contoh');
        $this->get('/tentang')->assertSee('Desa Contoh');
    }

    public function test_dashboard_menampilkan_angka_dari_database(): void
    {
        Agenda::create(['judul' => 'A', 'slug' => 'a', 'tanggal' => '2026-01-01', 'lokasi' => 'L', 'status' => 'Akan Datang']);
        $this->admin()->get('/admin')->assertOk()->assertViewHas('totalAgenda', 1);
    }
}
