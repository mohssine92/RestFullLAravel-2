<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler; /* video 64  */
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Throwable;


/* lo que hemos hecho en esta clase lo que se denomina redefinicion del ocmportamiento por default de laravel : es como se responda a las excepciones al momento de request segun nuestro requirimientos  y restringimiento etc ..
 * nosotros nuestro objetivo es estadarizar en respuesta json : como es el caso este .
 * ya tenemos un contro casi absoluto de la excepciones relacionadas con http en esta class, nos falta control de la excepciones directamenterelacionada con la manipulacion de los datos , y finalmente controlar excepciones que pueden ser inesperadas
*/
class Handler extends ExceptionHandler
{


    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {


       // no me interesa la excepciones con detalles en este caso en produccion , asi en config/app tenemos un indice infica si la app esta en modo de depuracion o productioon : lo usamos en restringir la ejeccion de los excepciones
        if( config('app.debug') === true  ) { // config() helpers poara el archivo de app
           return $this->handleException($request, $exception );
        } // si estamos en depuracion Obtenemos detalles completos del problema , en produccion no


        /*  durante la vida de nuestrta Api Rest full - sucedan excepciones inesperados . debemos preparnos para ellos
        *  pòr lo menos mandamos mensaje al user que usa la api , un mensaje informativo que algo sucedo mal que no tenemos aun solucion
        */ // => 500 ES ALGO ENTORNO  DEL SERVIDOR  - no tiene nada que ver este err con el cliente o la peticion . es algo de l apiRestful algo enterno del servidor
        // imagenemos problema de establecer conexion con db se ha caido , este es un err inesperado
        return $this->errorResponse('Falla inesperada. Intente luego', 500);

        return parent::render($request, $exception); // es la primera vez que veo dos retuens legales
    }


    public function handleException($request, Throwable $exception){


        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }


        if ($exception instanceof ModelNotFoundException) { // si la instancia es del tipo  ModelNotFoundException , este tipo se dispar cuando el modelo consultado no se ha encontrado

           // class_basename : elemmina el espacio de nombre : no es bueno returnamos la estructura de nuestra app - asi lo limpia de returna solamente el nombre del modelo
           $modelo =  class_basename($exception->getModel()); // en la excepcion obtiene me el modelo orem que no se puede encontrar  ...
           // el caso es request un user con id no existe .

           return $this->errorResponse("No existe Ninguna Instancia de {$modelo} con el id espeficico", 404);  // metodo de mi trait : estadarizar la salida de la api
        }

        if ( $exception instanceof AuthenticationException ) { // si exisitira la excepcion del tipo cuando realmente requerimos autenticacion - requerida  para request y el request no la tiene
           return $this->unauthenticated($request, $exception);
        }

        if ( $exception instanceof AuthorizationException ) { // si exisitira la excepcion del tipo ... - en caso de que una accion requiere algun tipo de autorizacion : como rol admin etc : objetivo en la excepcion respondemos con json() con me¡nsaje ..
           return $this->errorResponse('No pose de permisos para ejecutar esta accion', 403 );
        }

        if ( $exception instanceof NotFoundHttpException ) {   // cuando el user inenta request a un url o ruta no existe apartir de mi dominio . excepcion not found => respondemos con json()
           return $this->errorResponse ( 'No se encontro la URL especificada', 404 );

        }


        if ($exception instanceof MethodNotAllowedHttpException) { // esta excepcion cuando la ruta existe es la misma pero el user se ha equivocado en reuest en metodo http repito pero debe existir la ruta/
           return $this->errorResponse('El método especificado en la petición no es válido', 405);
        }


         /* hemos manejado las excepciones mas comunes y hemos reurnado respuesta en formato json estandarizada : correcto
          * Pero en la realidad existen muchos excepciones http que no son comunes : lo que vamos a hacer es returnar en respuesta json un mensaje generalizados para estas mismas
          * es decir esta altura controlar de manere general cualquier instancia de excepcion http - asi este metodo sera la ultima en disparar
          * de esta forma estamos controlando cualquier otra excepcion que no se encuentra de las que hemos definido
         */
        if ($exception instanceof HttpException ) {
           return $this->errorResponse( $exception->getMessage(), $exception->getStatusCode() );
           // en este caso obtenemos mensaje de la excpcion directamente y el codigo de la misma y lo mandamos como mensaje en la respuesta de json
        }

        if ($exception instanceof QueryException ) { // violation de integridad : ententar a elminar objeto de db relacionado a otro objeto

          // dd( $exception );  // este es un helpers nos pinta lo que trae la instacian
           $codigo = $exception->errorInfo[1];

           if ($codigo == 1451) {
               return $this->errorResponse( 'No se puede eliminar de forma permamente el recurso porque está relacionado con algún otro.'  , 409 );
           } // el user de la api debe saber que debe eleminar eleminar antes la relacion y luego : como eleminar las transaccion y luego user . no puede eleminar user y dejar transaccion object de un user no conocido : no es logico : este es el conflicto
        }


    }



    /**
     * Convert an authentication exception into an unauthenticated response. requiere auteticacion
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception) // requerida autenticacion por parte del user - en request hacia la api
    {
        return $this->errorResponse('No autenticado.', 401); // funcion de trait returna
        //TODO : este comportamiente lo verificamos mas adelante en una seccion posterior - si returena en json o salga algo raro

    }


     /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request) // video 64 : siempre returnamos respuesta json aunque falla la validation ; Hemos redefinido metodo : romper el comportamiento de laravel por defecto
    {
        $errors = $e->validator->errors()->getMessages(); // aqui lo que equivale acumulador de errs

        return $this->errorResponse( $errors, 422 ); // metodo de mi trait : para transformar respuesta en json : salida de la api : estadarizada
    }







}
