<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function index(Request $request): View
    {
        $torneos = $request->user()->torneos()
            ->withCount('inscripciones')
            ->orderBy('fecha')
            ->get();

        return view('jugador.mis-torneos', compact('torneos'));
    }

    public function store(Request $request, Torneo $torneo): RedirectResponse
    {
        $user = $request->user();

        try {
            $error = DB::transaction(function () use ($torneo, $user) {
                $torneo = Torneo::lockForUpdate()->withCount('inscripciones')->findOrFail($torneo->id);

                if ($torneo->tieneInscrito($user)) {
                    return 'Ya estás inscrito en este torneo.';
                }

                if ($motivo = $torneo->motivoNoDisponible()) {
                    return $motivo;
                }

                $torneo->inscripciones()->create(['user_id' => $user->id]);

                return null;
            });
        } catch (UniqueConstraintViolationException) {
            $error = 'Ya estás inscrito en este torneo.';
        }

        if ($error) {
            return back()->with('error', $error);
        }

        return redirect()->route('torneos.show', $torneo)
            ->with('status', "Te inscribiste en \"{$torneo->nombre}\".");
    }

    public function destroy(Request $request, Torneo $torneo): RedirectResponse
    {
        $inscripcion = $torneo->inscripciones()->where('user_id', $request->user()->id)->first();

        if (! $inscripcion) {
            return back()->with('error', 'No estás inscrito en este torneo.');
        }

        if (! $torneo->permiteCancelar()) {
            return back()->with('error', 'Ya no puedes cancelar: la fecha del torneo llegó o ya pasó.');
        }

        $inscripcion->delete();

        return back()->with('status', "Cancelaste tu inscripción a \"{$torneo->nombre}\". Tu plaza quedó libre.");
    }
}
