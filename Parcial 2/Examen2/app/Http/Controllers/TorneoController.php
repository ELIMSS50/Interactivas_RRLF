<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TorneoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = $request->input('q');
        $juego = $request->input('juego');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $consulta = Torneo::disponibles();

        if ($buscar != '') {
            $consulta->where(function ($query) use ($buscar) {
                $query->where('nombre', 'like', '%' . $buscar . '%');
                $query->orWhere('juego', 'like', '%' . $buscar . '%');
            });
        }

        if ($juego != '') {
            $consulta->where('juego', $juego);
        }

        if ($desde != '') {
            $consulta->whereDate('fecha', '>=', $desde);
        }

        if ($hasta != '') {
            $consulta->whereDate('fecha', '<=', $hasta);
        }

        $hayFiltros = false;
        if ($juego != '' || $desde != '' || $hasta != '') {
            $hayFiltros = true;
        }

        $torneos = $consulta
            ->withCount('inscripciones')
            ->orderBy('fecha')
            ->orderBy('nombre')
            ->paginate(9)
            ->withQueryString();

        $juegos = Torneo::select('juego')->distinct()->orderBy('juego')->get();

        $misTorneos = $request->user()?->inscripciones()->pluck('torneo_id') ?? collect();

        return view('torneos.listado', compact('torneos', 'misTorneos', 'juegos', 'hayFiltros'));
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
