@extends('layouts.app')

@section('titulo', 'Mis recetas')

@section('contenido')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Mis recetas</h1>
    <a href="{{ route('recetas.create') }}" class="rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">+ Nueva receta</a>
</div>

<form method="GET" action="{{ route('recetas.index') }}" class="bg-white rounded border p-4 mb-6 flex flex-col sm:flex-row gap-3">
    <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por título..."
           class="flex-1 rounded border border-gray-300 px-3 py-2">
    <select name="categoria" class="rounded border border-gray-300 px-3 py-2">
        <option value="">Todas las categorías</option>
        @foreach (\App\Models\Receta::CATEGORIAS as $cat)
            <option value="{{ $cat }}" @selected($categoria === $cat)>{{ $cat }}</option>
        @endforeach
    </select>
    <button class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-gray-900">Buscar</button>
    @if ($filtrando)
        <a href="{{ route('recetas.index') }}" class="rounded border border-gray-300 px-4 py-2 text-center hover:bg-gray-50">Limpiar</a>
    @endif
</form>

@if ($recetas->isEmpty())
    <div class="bg-white rounded border p-8 text-center text-gray-600">
        @if ($filtrando)
            No hay resultados para tu búsqueda o filtro.
        @else
            Aún no tienes recetas. <a href="{{ route('recetas.create') }}" class="text-orange-600 hover:underline">Crea la primera</a>.
        @endif
    </div>
@else
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($recetas as $receta)
            <div class="bg-white rounded border p-4 flex flex-col">
                <a href="{{ route('recetas.show', $receta) }}" class="font-semibold text-lg hover:text-orange-600">{{ $receta->titulo }}</a>
                <div class="text-sm text-gray-600 mt-1 space-x-2">
                    <span class="inline-block rounded bg-orange-100 text-orange-800 px-2">{{ $receta->categoria }}</span>
                    @if ($receta->tiempo) <span>{{ $receta->tiempo }} min</span> @endif
                    @if ($receta->dificultad) <span>· {{ $receta->dificultadTexto() }}</span> @endif
                </div>
                <div class="mt-auto pt-4 flex gap-3 text-sm">
                    <a href="{{ route('recetas.show', $receta) }}" class="text-gray-700 hover:underline">Ver</a>
                    <a href="{{ route('recetas.edit', $receta) }}" class="text-blue-600 hover:underline">Editar</a>
                    <form method="POST" action="{{ route('recetas.destroy', $receta) }}"
                          onsubmit="return confirm('¿Seguro que deseas eliminar esta receta?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $recetas->links() }}</div>
@endif
@endsection
