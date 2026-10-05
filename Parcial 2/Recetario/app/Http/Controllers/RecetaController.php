<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecetaRequest;
use App\Models\Receta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim((string) $request->query('buscar'));
        $categoria = $request->query('categoria');

        $recetas = $request->user()->recetas()
            ->when($buscar !== '', fn ($q) => $q->where('titulo', 'like', "%{$buscar}%"))
            ->when($categoria, fn ($q) => $q->where('categoria', $categoria))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('recetas.index', [
            'recetas' => $recetas,
            'buscar' => $buscar,
            'categoria' => $categoria,
            'filtrando' => $buscar !== '' || $categoria,
        ]);
    }

    public function create()
    {
        return view('recetas.create', ['receta' => new Receta]);
    }

    public function store(RecetaRequest $request)
    {
        $receta = $request->user()->recetas()->create($request->validated());

        return redirect()->route('recetas.show', $receta)->with('ok', 'Receta creada correctamente.');
    }

    public function show(Request $request, int $receta)
    {
        return view('recetas.show', ['receta' => $this->propia($request, $receta)]);
    }

    public function edit(Request $request, int $receta)
    {
        return view('recetas.edit', ['receta' => $this->propia($request, $receta)]);
    }

    public function update(RecetaRequest $request, int $receta)
    {
        $modelo = $this->propia($request, $receta);
        $modelo->update($request->validated());

        return redirect()->route('recetas.show', $modelo)->with('ok', 'Receta actualizada correctamente.');
    }

    public function destroy(Request $request, int $receta)
    {
        $this->propia($request, $receta)->delete();

        return redirect()->route('recetas.index')->with('ok', 'Receta eliminada.');
    }

    private function propia(Request $request, int $id): Receta
    {
        return $request->user()->recetas()->findOrFail($id);
    }
}
