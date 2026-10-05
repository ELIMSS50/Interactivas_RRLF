@extends('layouts.app')

@section('titulo', 'Editar receta')

@section('contenido')
<div class="max-w-3xl mx-auto bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">Editar receta</h1>
    <form method="POST" action="{{ route('recetas.update', $receta) }}" class="space-y-4" novalidate>
        @method('PUT')
        @include('recetas._form')
        <div class="flex gap-3">
            <button class="rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">Guardar cambios</button>
            <a href="{{ route('recetas.show', $receta) }}" class="rounded border border-gray-300 px-4 py-2 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
