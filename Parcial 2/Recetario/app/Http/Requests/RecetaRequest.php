<?php

namespace App\Http\Requests;

use App\Models\Receta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', Rule::in(Receta::CATEGORIAS)],
            'tiempo' => ['nullable', 'integer', 'min:1'],
            'dificultad' => ['nullable', Rule::in(array_keys(Receta::DIFICULTADES))],
            'ingredientes' => ['nullable', 'string'],
            'pasos' => ['nullable', 'string'],
            'nota' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener mas de 255 caracteres.',
            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.in' => 'Selecciona una categoria que este dentro de lo definido.',
            'tiempo.integer' => 'El tiempo debe ser un número entero de minutos.',
            'tiempo.min' => 'El tiempo debe ser mayor a 0 minutos.',
            'dificultad.in' => 'La dificultad solo puede ser Facil, Media o Dificil.',
            'nota.max' => 'La nota no puede tener mas de 1000 caracteres.',
        ];
    }
}
