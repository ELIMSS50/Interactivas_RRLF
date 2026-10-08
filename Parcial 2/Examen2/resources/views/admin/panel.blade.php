@extends('plantillas.app')

@section('title', 'Panel de administración')

@section('content')
    @php
        $estadisticas = [
            ['Torneos', \App\Models\Torneo::count(), 'trophy'],
            ['Abiertos', \App\Models\Torneo::where('estado', \App\Models\Torneo::ESTADO_ABIERTO)->count(), 'check-circle'],
            ['Disponibles', \App\Models\Torneo::disponibles()->count(), 'calendar'],
            ['Inscripciones', \App\Models\Inscripcion::count(), 'users'],
        ];
    @endphp

    <div class="mb-8 flex gap-4 items-end justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-600">Panel de administración</p>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Hola, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Resumen general de torneos e inscripciones.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.torneos.index') }}" class="btn btn-secondary">
                <x-icono name="list" class="size-4" />
                Gestionar torneos
            </a>
            <a href="{{ route('admin.torneos.create') }}" class="btn btn-primary">
                <x-icono name="plus" class="size-4" />
                Nuevo torneo
            </a>
        </div>
    </div>

    <dl class="grid gap-4 grid-cols-4">
        @foreach ($estadisticas as [$etiqueta, $valor, $icono])
            <div class="card flex items-center gap-4 p-5">
                <span class="flex size-11 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <x-icono :name="$icono" class="size-6" />
                </span>
                <div>
                    <dt class="text-sm text-slate-500">{{ $etiqueta }}</dt>
                    <dd class="text-2xl font-bold text-slate-900">{{ $valor }}</dd>
                </div>
            </div>
        @endforeach
    </dl>
@endsection
