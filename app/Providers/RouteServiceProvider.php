<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;



/*
 * especifica como funcionar las rutas tanto para la api como para la aplicacion web .
*/
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
     protected $namespace = 'App\\Http\\Controllers';  /* hemos descomentado esta propiedad , para el tema de crear la rutas hacia controlador  */ /* indicamos a laravel automaticamente hacia donde referirse cuando le indicamos un nombre de un
     controller */

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

         /* RouteServiceProvider especifica la manera en la que funcciona tanto las routas para la api como las routas para la apliacacion web   */



    }

    /* => hemos creado este metodo que aun se sigue llamada de una manera automatizada  en laravel aprovechandola para implementar estos 2  metodos de una manera mas oganizada map() es una function publica*/
    public function map ()
    {

       $this->mapApiRoutes();  /* como el metodo esta llamada en laravel de una manera automatizada , estos dos funccion se ejecutan de una manara automatizada */
       $this->mapWebRoutes();

    }
     /* basicamente lo que estamos haciendo es separa los lugares donde se van registrarse estas routas */




    protected function mapApiRoutes()
    {
         Route::middleware('api')  /* para los de la api esta haciendo uso de un groupo de moddleware llamado api  */  // ver Kernel $middlewareGroups
         // prefix('api') => en este caso se trata de api de nivel superior , comento el prefijo porque en la peticion quiero que sea atendida en el raiz como tal: apiresful.dev/
         ->namespace($this->namespace) /* => hacer llamada al metodo name space indicandole que se haga uso del atributo namespace que nosotros acabamos de ,,,,,,,,,,,, */
         ->group(base_path('routes/api.php'));

    }

    protected function mapWebRoutes()
    {

        Route::middleware('web')   /*  vemos para aplicacion web esta haciendo uso de un groupo de moddleware llamado web  */ // ver Kernel $middlewareGroups
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));


    }





    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
