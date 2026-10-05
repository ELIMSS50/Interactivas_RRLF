@csrf
<p class="text-sm text-gray-500">Los campos con * son obligatorios.</p>
<div>
    <label for="titulo" class="block text-sm font-medium mb-1">Título *</label>
    <input id="titulo" name="titulo" type="text" value="{{ old('titulo', $receta->titulo) }}"
           class="w-full rounded border px-3 py-2 @error('titulo') border-red-500 @else border-gray-300 @enderror">
    @error('titulo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid gap-4 sm:grid-cols-3">
    <div>
        <label for="categoria" class="block text-sm font-medium mb-1">Categoría *</label>
        <select id="categoria" name="categoria"
                class="w-full rounded border px-3 py-2 @error('categoria') border-red-500 @else border-gray-300 @enderror">
            <option value="">Selecciona...</option>
            @foreach (\App\Models\Receta::CATEGORIAS as $cat)
                <option value="{{ $cat }}" @selected(old('categoria', $receta->categoria) === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('categoria') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="tiempo" class="block text-sm font-medium mb-1">Tiempo (minutos)</label>
        <input id="tiempo" name="tiempo" type="number" min="1" value="{{ old('tiempo', $receta->tiempo) }}"
               class="w-full rounded border px-3 py-2 @error('tiempo') border-red-500 @else border-gray-300 @enderror">
        @error('tiempo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="dificultad" class="block text-sm font-medium mb-1">Dificultad</label>
        <select id="dificultad" name="dificultad"
                class="w-full rounded border px-3 py-2 @error('dificultad') border-red-500 @else border-gray-300 @enderror">
            <option value="">Sin especificar</option>
            @foreach (\App\Models\Receta::DIFICULTADES as $valor => $texto)
                <option value="{{ $valor }}" @selected(old('dificultad', $receta->dificultad) === $valor)>{{ $texto }}</option>
            @endforeach
        </select>
        @error('dificultad') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="ingredientes" class="block text-sm font-medium mb-1">Ingredientes <span class="text-gray-500 font-normal">(uno por línea)</span></label>
    <textarea id="ingredientes" name="ingredientes" rows="6"
              class="w-full rounded border px-3 py-2 @error('ingredientes') border-red-500 @else border-gray-300 @enderror">{{ old('ingredientes', $receta->ingredientes) }}</textarea>
    @error('ingredientes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label for="pasos" class="block text-sm font-medium mb-1">Pasos de preparación <span class="text-gray-500 font-normal">(uno por línea)</span></label>
    <textarea id="pasos" name="pasos" rows="6"
              class="w-full rounded border px-3 py-2 @error('pasos') border-red-500 @else border-gray-300 @enderror">{{ old('pasos', $receta->pasos) }}</textarea>
    @error('pasos') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label for="nota" class="block text-sm font-medium mb-1">Nota personal</label>
    <textarea id="nota" name="nota" rows="3" placeholder="Opcional: aquí puedes añadir tus comentarios o sugerencias sobre la receta."
              class="w-full rounded border px-3 py-2 @error('nota') border-red-500 @else border-gray-300 @enderror">{{ old('nota', $receta->nota) }}</textarea>
    @error('nota') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>
