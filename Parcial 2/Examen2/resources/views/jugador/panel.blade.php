@extends('plantillas.app')

@section('title', 'Panel del jugador')

@section('content')
    @php
        $jugador = auth()->user();
        $proximos = $jugador->torneos()->whereDate('fecha', '>=', today())->orderBy('fecha')->get();
        $estadisticas = [
            ['Inscripciones', $jugador->inscripciones()->count(), 'list'],
            ['Próximos torneos', $proximos->count(), 'calendar'],
            ['Torneos disponibles', \App\Models\Torneo::disponibles()->count(), 'trophy'],
        ];
    @endphp

    <div class="mb-8 flex gap-4 items-end justify-between">
        <div>
            <p class="text-sm font-medium text-indigo-600">Panel del jugador</p>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Hola, {{ $jugador->name }}</h1>
            <p class="mt-1 text-sm text-slate-500">Consulta tus inscripciones y encuentra nuevos torneos.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mis-torneos') }}" class="btn btn-secondary">
                <x-icono name="list" class="size-4" />
                Mis torneos
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <x-icono name="trophy" class="size-4" />
                Ver torneos disponibles
            </a>
        </div>
    </div>

    <dl class="mb-8 grid gap-4 grid-cols-3">
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

    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Tus próximos torneos</h2>
        </div>
        @forelse ($proximos as $torneo)
            <a href="{{ route('torneos.show', $torneo) }}" class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4 last:border-0 hover:bg-slate-50">
                <div class="flex items-center gap-4">
                    <div class="flex w-12 flex-col items-center rounded-lg bg-slate-100 py-1.5">
                        <span class="text-xs font-medium text-slate-500 uppercase">{{ $torneo->fecha->translatedFormat('M') }}</span>
                        <span class="text-lg leading-tight font-bold text-slate-900">{{ $torneo->fecha->format('d') }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $torneo->nombre }}</p>
                        <p class="text-sm text-slate-500">{{ $torneo->juego }}</p>
                    </div>
                </div>
                <x-icono name="arrow-right" class="size-4 text-slate-400" />
            </a>
        @empty
            <div class="px-5 py-12 text-center">
                <x-icono name="calendar" class="mx-auto mb-3 size-8 text-slate-300" />
                <p class="font-medium text-slate-900">No tienes torneos próximos</p>
                <p class="text-sm text-slate-500">Revisa los torneos disponibles e inscríbete.</p>
            </div>
        @endforelse
    </div>
@endsection
