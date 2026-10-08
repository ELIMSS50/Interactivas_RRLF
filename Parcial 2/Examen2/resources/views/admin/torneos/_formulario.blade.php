@csrf

<div class="space-y-6">
    <div>
        <label for="nombre" class="label">Nombre <span class="text-rose-500">*</span></label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $torneo->nombre) }}" required maxlength="255"
               class="input @error('nombre') input-error @enderror">
        @error('nombre')
            <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="juego" class="label">Juego o deporte <span class="text-rose-500">*</span></label>
        <input id="juego" type="text" name="juego" value="{{ old('juego', $torneo->juego) }}" required maxlength="100"
               placeholder="Ej. FIFA, Ajedrez, Fútbol"
               class="input @error('juego') input-error @enderror">
        @error('juego')
            <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 grid-cols-3">
        <div>
            <label for="fecha" class="label">Fecha <span class="text-rose-500">*</span></label>
            <input id="fecha" type="date" name="fecha" value="{{ old('fecha', $torneo->fecha?->format('Y-m-d')) }}" required
                   min="{{ now()->addDay()->format('Y-m-d') }}"
                   class="input @error('fecha') input-error @enderror">
            @error('fecha')
                <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
            @else
                <p class="mt-1.5 text-xs text-slate-500">Debe ser una fecha futura.</p>
            @enderror
        </div>

        <div>
            <label for="cupo" class="label">Cupo <span class="text-rose-500">*</span></label>
            <input id="cupo" type="number" name="cupo" value="{{ old('cupo', $torneo->cupo) }}" required
                   min="{{ max(\App\Models\Torneo::CUPO_MIN, $torneo->inscripciones_count ?? 0) }}" max="{{ \App\Models\Torneo::CUPO_MAX }}"
                   class="input @error('cupo') input-error @enderror">
            @error('cupo')
                <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
            @else
                <p class="mt-1.5 text-xs text-slate-500">
                    Entre {{ \App\Models\Torneo::CUPO_MIN }} y {{ \App\Models\Torneo::CUPO_MAX }}.
                    @if ($torneo->exists)
                        Inscritos: {{ $torneo->inscripciones_count }}.
                    @endif
                </p>
            @enderror
        </div>

        <div>
            <label for="estado" class="label">Estado <span class="text-rose-500">*</span></label>
            <select id="estado" name="estado" required class="input @error('estado') input-error @enderror">
                @foreach (\App\Models\Torneo::ESTADOS as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $torneo->estado) === $estado)>{{ ucfirst($estado) }}</option>
                @endforeach
            </select>
            @error('estado')
                <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="descripcion" class="label">Descripción <span class="font-normal text-slate-400">(opcional)</span></label>
        <textarea id="descripcion" name="descripcion" rows="4" maxlength="2000"
                  class="input @error('descripcion') input-error @enderror">{{ old('descripcion', $torneo->descripcion) }}</textarea>
        @error('descripcion')
            <p class="field-error"><x-icono name="x-circle" class="size-4" />{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
    <a href="{{ route('admin.torneos.index') }}" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">
        <x-icono name="check" class="size-4" />
        {{ $boton }}
    </button>
</div>
