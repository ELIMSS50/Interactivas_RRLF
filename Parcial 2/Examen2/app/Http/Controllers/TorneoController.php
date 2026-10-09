<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TorneoController extends Controller
{
    public function index(Request $request): View
    {
        $torneos = Torneo::disponibles()
            ->withCount('inscripciones')
            ->orderBy('fecha')
            ->orderBy('nombre')
            ->paginate(9);

        $misTorneos = $request->user()?->inscripciones()->pluck('torneo_id') ?? collect();

        return view('torneos.listado', compact('torneos', 'misTorneos'));
    }

    public function show(Request $request, Torneo $torneo): View
    {
        $torneo->loadCount('inscripciones');

        $participantes = $torneo->jugadores()
            ->orderBy('inscripciones.created_at')
            ->get(['users.id', 'users.name']);

        $inscrito = $torneo->tieneInscrito($request->user());

        return view('torneos.detalle', compact('torneo', 'participantes', 'inscrito'));
    }
}
