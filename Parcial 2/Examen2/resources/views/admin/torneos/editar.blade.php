@extends('plantillas.app')

@section('title', 'Editar torneo')

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.torneos.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-900">
            <x-icono name="arrow-left" class="size-4" />
            Volver a gestión de torneos
        </a>

        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Editar torneo</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $torneo->nombre }}</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('admin.torneos.update', $torneo) }}">
                @method('PUT')
                @include('admin.torneos._formulario', ['boton' => 'Guardar cambios'])
            </form>
        </div>
    </div>
@endsection
