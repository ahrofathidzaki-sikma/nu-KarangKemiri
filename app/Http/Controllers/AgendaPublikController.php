<?php
namespace App\Http\Controllers;

use App\Models\Agenda;

class AgendaPublikController extends Controller
{
    public function index()
    {
        return view('public.agenda-index', [
            'akanDatang' => Agenda::akanDatang()->get(),
            'selesai' => Agenda::selesai()->paginate(8),
        ]);
    }

    public function show(Agenda $agenda)
    {
        $agenda->load('dokumentasis');
        return view('public.agenda-show', compact('agenda'));
    }
}
