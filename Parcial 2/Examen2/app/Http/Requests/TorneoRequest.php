<?php

namespace App\Http\Requests;

use App\Models\Torneo;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TorneoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('cupo') === null || $this->input('cupo') === '') {
            $this->merge(['cupo' => Torneo::CUPO_DEFECTO]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $torneo = $this->route('torneo');
        $inscritos = $torneo instanceof Torneo ? $torneo->inscripciones()->count() : 0;

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'juego' => ['required', 'string', 'max:100'],
            'fecha' => ['required', 'date', 'after:today'],
            'cupo' => [
                'required',
                'integer',
                'between:'.Torneo::CUPO_MIN.','.Torneo::CUPO_MAX,
                function (string $attribute, mixed $value, Closure $fail) use ($inscritos) {
                    if ((int) $value < $inscritos) {
                        $fail("No puedes reducir el cupo por debajo de los {$inscritos} jugadores ya inscritos.");
                    }
                },
            ],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'estado' => ['required', Rule::in(Torneo::ESTADOS)],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'juego.required' => 'El juego o deporte es obligatorio.',
            'juego.max' => 'El juego o deporte no puede tener más de 100 caracteres.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'fecha.after' => 'La fecha debe ser futura.',
            'cupo.required' => 'El cupo es obligatorio.',
            'cupo.integer' => 'El cupo debe ser un número entero.',
            'cupo.between' => 'El cupo debe estar entre '.Torneo::CUPO_MIN.' y '.Torneo::CUPO_MAX.'.',
            'descripcion.max' => 'La descripción no puede tener más de 2000 caracteres.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser abierto o cerrado.',
        ];
    }
}
