<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;


/*
 * los middelwares basicamente son elementos que se ejcutan antes de atender una peticion dada .
 * buscando realizar modificaciones o comprabaciones en la misma peticion por ej : verificar rol del user que esta relizando la peticion o si el mismo user esta autenticado ..
*/
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
