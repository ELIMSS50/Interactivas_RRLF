<?php

namespace Database\Seeders;

use App\Models\Torneo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jugadores = collect([
            'ana' => 'Ana López',
            'carlos' => 'Carlos Pérez',
            'maria' => 'María García',
            'pedro' => 'Pedro Sánchez',
        ])->map(function (string $nombre, string $usuario) {
            $jugador = User::firstOrNew(['email' => "{$usuario}@torneos.com"]);
            $jugador->name = $nombre;
            $jugador->password = 'jugador123';
            $jugador->role = User::ROLE_JUGADOR;
            $jugador->email_verified_at = now();
            $jugador->save();

            return $jugador;
        });

        $torneos = [
            [
                'datos' => ['juego' => 'FIFA', 'fecha' => today()->addDays(7), 'cupo' => 16, 'estado' => 'abierto',
                    'descripcion' => "Torneo 1 vs 1 en consola.\nTrae tu propio control."],
                'nombre' => 'Copa FIFA Otoño',
                'inscritos' => ['ana', 'carlos'],
            ],
            [
                'datos' => ['juego' => 'Ajedrez', 'fecha' => today()->addDays(14), 'cupo' => 8, 'estado' => 'abierto',
                    'descripcion' => 'Partidas rápidas de 5 minutos por jugador.'],
                'nombre' => 'Torneo de Ajedrez Relámpago',
                'inscritos' => ['maria'],
            ],
            [
                'datos' => ['juego' => 'Smash Bros', 'fecha' => today()->addDays(30), 'cupo' => 32, 'estado' => 'abierto',
                    'descripcion' => null],
                'nombre' => 'Gran Final Smash',
                'inscritos' => [],
            ],
            [
                'datos' => ['juego' => 'Pádel', 'fecha' => today()->addDays(10), 'cupo' => 2, 'estado' => 'abierto',
                    'descripcion' => 'Torneo lleno para probar el bloqueo por cupo.'],
                'nombre' => 'Liga de Pádel Express',
                'inscritos' => ['carlos', 'maria'],
            ],
            [
                'datos' => ['juego' => 'Tenis', 'fecha' => today()->addDays(20), 'cupo' => 16, 'estado' => 'cerrado',
                    'descripcion' => 'Torneo cerrado para probar el bloqueo por estado.'],
                'nombre' => 'Copa de Tenis Cerrada',
                'inscritos' => ['ana'],
            ],
            [
                'datos' => ['juego' => 'Fútbol', 'fecha' => today(), 'cupo' => 10, 'estado' => 'abierto',
                    'descripcion' => 'Se juega hoy: ya no admite inscripciones ni cancelaciones.'],
                'nombre' => 'Fútbol Rápido de Hoy',
                'inscritos' => ['ana'],
            ],
        ];

        foreach ($torneos as $item) {
            $torneo = Torneo::updateOrCreate(['nombre' => $item['nombre']], $item['datos']);

            foreach ($item['inscritos'] as $usuario) {
                $torneo->inscripciones()->firstOrCreate(['user_id' => $jugadores[$usuario]->id]);
            }
        }
    }
}
