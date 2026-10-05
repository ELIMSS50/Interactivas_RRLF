@extends('layouts.app')

@section('titulo', 'Nueva receta')

@section('contenido')
<div class="max-w-3xl mx-auto bg-white rounded border p-6">
    <h1 class="text-xl font-semibold mb-4">Nueva receta</h1>
    <form method="POST" action="{{ route('recetas.store') }}" class="space-y-4" novalidate>
        @include('recetas._form')
        <div class="flex gap-3">
            <button class="rounded bg-orange-600 px-4 py-2 text-white hover:bg-orange-700">Guardar</button>
            <a href="{{ route('recetas.index') }}" class="rounded border border-gray-300 px-4 py-2 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
