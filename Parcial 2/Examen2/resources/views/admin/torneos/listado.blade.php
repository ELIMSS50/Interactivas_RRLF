@extends('plantillas.app')

@section('title', 'Gestión de torneos')

@section('content')
    <div class="mb-6 flex gap-4 items-end justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Gestión de torneos</h1>
            <p class="mt-1 text-sm text-slate-500">Crea, edita y elimina torneos, y administra sus inscritos.</p>
        </div>
        <a href="{{ route('admin.torneos.create') }}" class="btn btn-primary">
            <x-icono name="plus" class="size-4" />
            Nuevo torneo
        </a>
    </div>

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
                    @forelse ($torneos as $torneo)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $torneo->nombre }}</p>
                                <p class="text-xs text-slate-500">{{ $torneo->juego }}</p>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-600">{{ $torneo->fecha->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 whitespace-nowrap text-slate-700">
                                {{ $torneo->inscritos() }}/{{ $torneo->cupo }}
                                <span @class(['ml-1 font-medium', 'text-rose-600' => $torneo->estaLleno(), 'text-emerald-600' => ! $torneo->estaLleno()])>
                                    {{ $torneo->estaLleno() ? 'Lleno' : 'Libre' }}
                                </span>
                            </td>
                            <td @class(['px-5 py-4 font-medium', 'text-emerald-600' => $torneo->estaAbierto(), 'text-rose-600' => ! $torneo->estaAbierto()])>
                                {{ ucfirst($torneo->estado) }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.torneos.inscripciones', $torneo) }}" class="btn btn-secondary btn-sm" title="Ver inscritos">
                                        <x-icono name="users" class="size-4" />
                                        Inscritos
                                    </a>
                                    <a href="{{ route('admin.torneos.edit', $torneo) }}" class="btn btn-secondary btn-sm" title="Editar">
                                        <x-icono name="pencil" class="size-4" />
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.torneos.destroy', $torneo) }}"
                                          onsubmit="return confirm('¿Eliminar el torneo &quot;{{ $torneo->nombre }}&quot;? También se eliminarán sus {{ $torneo->inscripciones_count }} inscripción(es).')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <x-icono name="trash" class="size-4" />
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <x-icono name="inbox" class="mx-auto mb-3 size-8 text-slate-300" />
                                <p class="font-medium text-slate-900">Aún no hay torneos registrados</p>
                                <p class="text-sm text-slate-500">Crea el primero con el botón "Nuevo torneo".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $torneos->links() }}
    </div>
@endsection
