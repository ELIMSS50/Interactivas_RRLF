<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use App\Models\Torneo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InscripcionController extends Controller
{
    public function index(Torneo $torneo): View
    {
        $torneo->loadCount('inscripciones');

        $inscripciones = $torneo->inscripciones()
            ->with('user:id,name,email')
            ->orderBy('created_at')
            ->get();

        return view('admin.torneos.inscripciones', compact('torneo', 'inscripciones'));
    }

    public function destroy(Inscripcion $inscripcion): RedirectResponse
    {
        $inscripcion->load('user', 'torneo');
        $inscripcion->delete();

        return redirect()->route('admin.torneos.inscripciones', $inscripcion->torneo)
            ->with('status', "Se dio de baja a {$inscripcion->user->name} del torneo \"{$inscripcion->torneo->nombre}\". La plaza quedó libre.");
    }
}
