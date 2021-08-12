<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;


/*
 * los providers en general son clases que podemos utulizar para realizar el registro de algun tipo de dependencia o algun tipo de funcionalidad que queremos que sea ejecutada de manera automatizada durante
 * la ejecuccion de una peticion : digamos asi durante el comienzo de la ejecuccion del framwork como tal para atender una peticion especifica .
 * cada uno de los classes providers tiene una funcionalidad clara y definida .
*/
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);  /* especificar por defecto numero de caracteres por el asunto de charset en base de datos - suporte de emoticones V: 39 */
    }
}
