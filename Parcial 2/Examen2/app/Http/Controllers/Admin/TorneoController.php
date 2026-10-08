<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TorneoRequest;
use App\Models\Torneo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TorneoController extends Controller
{
    public function index(): View
    {
        $torneos = Torneo::withCount('inscripciones')
            ->orderBy('fecha')
            ->paginate(10);

        return view('admin.torneos.listado', compact('torneos'));
    }

    public function create(): View
    {
        return view('admin.torneos.crear', ['torneo' => new Torneo]);
    }

    public function store(TorneoRequest $request): RedirectResponse
    {
        $torneo = Torneo::create($request->validated());

        return redirect()->route('admin.torneos.index')
            ->with('status', "Torneo \"{$torneo->nombre}\" creado correctamente.");
    }

    public function edit(Torneo $torneo): View
    {
        $torneo->loadCount('inscripciones');

        return view('admin.torneos.editar', compact('torneo'));
    }

    public function update(TorneoRequest $request, Torneo $torneo): RedirectResponse
    {
        $torneo->update($request->validated());

        return redirect()->route('admin.torneos.index')
            ->with('status', "Torneo \"{$torneo->nombre}\" actualizado correctamente.");
    }

    public function destroy(Torneo $torneo): RedirectResponse
    {
        $nombre = $torneo->nombre;
        $inscritos = $torneo->inscripciones()->count();

        DB::transaction(fn () => $torneo->delete());

        return redirect()->route('admin.torneos.index')
            ->with('status', "Torneo \"{$nombre}\" eliminado junto con {$inscritos} inscripción(es).");
    }
}
