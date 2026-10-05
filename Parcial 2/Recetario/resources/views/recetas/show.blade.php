@extends('layouts.app')

@section('titulo', $receta->titulo)

@section('contenido')
<div class="max-w-3xl mx-auto bg-white rounded border p-6">
    <a href="{{ route('recetas.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Volver a mis recetas</a>

    <h1 class="text-2xl font-semibold mt-2">{{ $receta->titulo }}</h1>
    <div class="text-sm text-gray-600 mt-1 space-x-2">
        <span class="inline-block rounded bg-orange-100 text-orange-800 px-2">{{ $receta->categoria }}</span>
        @if ($receta->tiempo) <span>{{ $receta->tiempo }} min</span> @endif
        @if ($receta->dificultad) <span>· {{ $receta->dificultadTexto() }}</span> @endif
    </div>

    <h2 class="font-semibold mt-6 mb-2">Ingredientes</h2>
    @forelse ($receta->listaIngredientes() as $ingrediente)
        @if ($loop->first) <ul class="list-disc pl-6 space-y-1"> @endif
        <li>{{ $ingrediente }}</li>
        @if ($loop->last) </ul> @endif
    @empty
        <p class="text-gray-500">Sin ingredientes registrados.</p>
    @endforelse

    <h2 class="font-semibold mt-6 mb-2">Pasos de preparación</h2>
    @forelse ($receta->listaPasos() as $paso)
        @if ($loop->first) <ol class="list-decimal pl-6 space-y-1"> @endif
        <li>{{ $paso }}</li>
        @if ($loop->last) </ol> @endif
    @empty
        <p class="text-gray-500">Sin pasos registrados.</p>
    @endforelse

    @if ($receta->nota)
        <h2 class="font-semibold mt-6 mb-2">Nota personal</h2>
        <p class="rounded bg-yellow-50 border border-yellow-200 px-4 py-3 whitespace-pre-line">{{ $receta->nota }}</p>
    @endif

    <div class="flex gap-3 mt-8">
        <a href="{{ route('recetas.edit', $receta) }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Editar</a>
        <form method="POST" action="{{ route('recetas.destroy', $receta) }}"
              onsubmit="return confirm('¿Seguro que deseas eliminar esta receta?')">
            @csrf
            @method('DELETE')
            <button class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700">Eliminar</button>
        </form>
    </div>
</div>
@endsection
