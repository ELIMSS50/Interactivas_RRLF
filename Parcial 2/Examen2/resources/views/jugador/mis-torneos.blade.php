@extends('plantillas.app')

@section('title', 'Mis torneos')

@section('content')
    <div class="mb-6 flex gap-4 items-end justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Mis torneos</h1>
            <p class="mt-1 text-sm text-slate-500">Torneos en los que estás inscrito. Puedes cancelar hasta el día anterior al torneo.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <x-icono name="trophy" class="size-4" />
            Buscar torneos
        </a>
    </div>

    @if ($torneos->isEmpty())
        <div class="card flex flex-col items-center px-6 py-16 text-center">
            <span class="mb-4 flex size-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                <x-icono name="inbox" class="size-6" />
            </span>
            <h2 class="text-lg font-semibold text-slate-900">Todavía no estás inscrito en ningún torneo</h2>
            <p class="mt-1 text-sm text-slate-500">Explora los torneos disponibles y asegura tu plaza.</p>
            <a href="{{ route('home') }}" class="btn btn-secondary mt-5">Ver torneos disponibles</a>
        </div>
    @else
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="table-head">
                        <tr>
                            <th class="px-5 py-3">Torneo</th>
                            <th class="px-5 py-3">Fecha</th>
                            <th class="px-5 py-3">Ocupación</th>
                            <th class="px-5 py-3">Estado</th>
                            <th class="px-5 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($torneos as $torneo)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <a href="{{ route('torneos.show', $torneo) }}" class="font-semibold text-slate-900 hover:text-indigo-600">{{ $torneo->nombre }}</a>
                                    <p class="text-xs text-slate-500">{{ $torneo->juego }}</p>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-slate-600">{{ $torneo->fecha->format('d/m/Y') }}</td>
                                <td class="w-48 px-5 py-4">
                                    <x-cupo :torneo="$torneo" />
                                </td>
                                <td class="px-5 py-4">
                                    <x-estado :torneo="$torneo" />
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($torneo->permiteCancelar())
                                        <form method="POST" action="{{ route('torneos.cancelar', $torneo) }}"
                                              onsubmit="return confirm('¿Cancelar tu inscripción a &quot;{{ $torneo->nombre }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <x-icono name="x-circle" class="size-4" />
                                                Cancelar inscripción
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                            <x-icono name="lock" class="size-4" />
                                            Ya no se puede cancelar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
