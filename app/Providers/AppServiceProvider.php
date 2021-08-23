<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailchanged;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        /* registro de un event de modelo de laravel  video 111
          simplemente cuando un product sea actualizado se ejecute lo siguiente
        */
        Product::updated(function($product) {
            if ($product->quantity == 0  && $product->estaDisponible() ) {

                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();

            }
        });

        // regitro de vent para user , atender evento de creaated
        User::created(function($user) {
            retry(5, function() use ($user) { // 126 helper de laravel - MAnejando acciones propensas a errores , lo intenta 5 veces y 100 ms ebtre intento y otro

             Mail::to($user)->send(new UserCreated($user)); // usar facede de mail enviando correo a un destinatario , laravel demanera automatica obtiene email del user acade de craerse , instancia del mailable
                                                // recuerda constructor del mailable class recibe una insatcia de user

            }, 100);

         /* NB : token verification esta en hidden asi si aplico peticion al servicio este campo sera occulto , pero en este caso atraves de event de created de laravel aplicado al modelo de user
                 obtengo el campo token_verification , asi sera pasao el objeto user completo al constructor de mailer class
          *      video 120
          */
        });


        // este event se dispara cada vez una instancia del modelo sera actualziada
        User::updated(function($user) {
            if ($user->isDirty('email')) { // actualizacion puede ser por cualquier otro attribute , en este caso nos interesa mandar correo solo si la direccion del correo electronico original cambio
                retry(5, function() use ($user) {

                  Mail::to($user)->send(new UserMailchanged($user));

                }, 100);
            }// isderty , no dice si especificamente el atrributes se ha cambiado 124
        });



    }



}

/* 111 - registra y atender este tipo de event existen diferentes maneras
     ver docs eloquent event
*/
