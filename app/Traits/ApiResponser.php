<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;



/* Tendra todo codico necesario para construir respuesta de nuestra Api video 61
 * estos metodos me estandarizan y ordenan las respuesta de api : como debe sera esperada por otros backends o frontend para el uso de estos recursos
 * estan usadas tanto en controllers u excepcion : redefinicion ed excepciones
*/
trait ApiResponser
{


    private function successResponse( $data, $code )
	{
	   return response()->json( $data, $code );
	}

    protected function errorResponse( $message, $code )
	{ // lo uso cuando quiero responder en un mesaje de err - cada vez tenemos elgun err , usamos este metodo y no json de manera directa
	   return response()->json([ 'error' => $message , 'code' => $code ], $code );
	}


    protected function showAll( Collection $collection, $code = 200 )
	{
		return $this->successResponse([ 'data' => $collection ] , $code );
	}

    protected function showOne( Model $instance, $code = 200 )
	{ // para returnar instancia especificas
	    return $this->successResponse([ 'data' => $instance ] , $code );
	}



}

