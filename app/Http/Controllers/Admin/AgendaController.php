<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    private function aturan(): array
    {
        return [
            'judul' => ['required', 'string', 'max:200'],
            'tanggal' => ['required', 'date'],
            'waktu' => ['nullable', 'date_format:H:i'],
            'lokasi' => ['required', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    public function index(Request $r)
    {
        $q = Agenda::query();
        if ($r->filled('status')) {
            $q->where('status', $r->status);
        }
        if ($r->filled('q')) {
            $q->where('judul', 'like', '%' . $r->q . '%');
        }
        return view('admin.agenda.index', ['agendas' => $q->orderByDesc('tanggal')->paginate(10)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.agenda.form', ['agenda' => new Agenda(['status' => 'Akan Datang'])]);
    }

    public function store(Request $r)
    {
        $data = $r->validate($this->aturan());
        $data['slug'] = Agenda::buatSlug($data['judul']);
        $data['status'] = Agenda::hitungStatus($data['tanggal'], $data['waktu'] ?? null);
        Agenda::create($data);
        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        return view('admin.agenda.form', compact('agenda'));
    }

    public function update(Request $r, Agenda $agenda)
    {
        $data = $r->validate($this->aturan());
        if ($data['judul'] !== $agenda->judul) {
            $data['slug'] = Agenda::buatSlug($data['judul'], $agenda->id);
        }
        $data['status'] = Agenda::hitungStatus($data['tanggal'], $data['waktu'] ?? null);
        $agenda->update($data);
        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete(); // dokumentasi terkait: agenda_id menjadi NULL (nullOnDelete)
        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil dihapus.');
    }
}
