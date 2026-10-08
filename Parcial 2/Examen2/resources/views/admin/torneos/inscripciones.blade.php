@extends('plantillas.app')

@section('title', 'Inscritos · '.$torneo->nombre)

@section('content')
    <a href="{{ route('admin.torneos.index') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-900">
        <x-icono name="arrow-left" class="size-4" />
        Volver a gestión de torneos
    </a>

    <div class="card mb-6 p-6">
        <div class="flex gap-6 items-center justify-between">
            <div>
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                        <x-icono name="tag" class="size-3.5" />
                        {{ $torneo->juego }}
                    </span>
                    <x-estado :torneo="$torneo" />
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $torneo->nombre }}</h1>
                <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500">
                    <x-icono name="calendar" class="size-4" />
                    {{ $torneo->fecha->translatedFormat('j \d\e F, Y') }}
                </p>
            </div>
            <x-cupo :torneo="$torneo" class="w-64" />
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Jugadores inscritos</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">Jugador</th>
                        <th class="px-5 py-3">Inscrito el</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($inscripciones as $inscripcion)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <x-avatar :nombre="$inscripcion->user->name" />
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $inscripcion->user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $inscripcion->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-600">{{ $inscripcion->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <form method="POST" action="{{ route('admin.inscripciones.destroy', $inscripcion) }}"
                                      onsubmit="return confirm('¿Dar de baja a &quot;{{ $inscripcion->user->name }}&quot; de este torneo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <x-icono name="user-minus" class="size-4" />
                                        Dar de baja
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <x-icono name="users" class="mx-auto mb-3 size-8 text-slate-300" />
                                <p class="font-medium text-slate-900">Sin jugadores inscritos</p>
                                <p class="text-sm text-slate-500">Este torneo aún no tiene inscripciones.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
