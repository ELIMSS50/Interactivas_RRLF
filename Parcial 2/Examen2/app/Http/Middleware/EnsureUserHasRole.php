<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Inicia sesión para continuar.');
        }

        if ($user->role !== $role) {
            return redirect()->route($user->rutaPanel())->with('error', 'No tienes permiso para entrar aquí.');
        }

        return $next($request);
    }
}
