<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController; // controlador base , usa trair : para generalzar respuesta de api
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer) // injeccion implicita del modelo
    {

      $sellers = $buyer->transactions()->with('product.seller')
          ->get()
          ->pluck('product.seller') // eso porque seller esta a interior de product (seguimos el orden relacion para la obtencion de las insatncias  ) (asi obtenemos solo los sellers relacionados a buyer)
          ->unique('id') /* es posible dentro  la coleccion se repita insatncias de seller por eso (asegurarnos que los valores incluidos en la coleccion sellan unicos)
                            metodo unico() conserva los indices originales de la coleccion : es decir si se repita un id seller sera borrado asi su indice queda como vacio
                            asi que resuelta elementos vacios dentro de nuestra coleccion producto de unique() funcion */
          ->values(); // por lo cual no es lo desado , asi values() reordena la coleccion y elemina los indices aquellos sus valores vacios (esto es todo )

      // dd($sellers); => es un helpers para debugar

      return $this->showAll($sellers);


    }
    /* 91 . obtener vendedores de un comprador , usando relaciones de forma indirecta es decir
     * seller -> hasMany transactions -> transaction belongsTo product -> producto -> belogsto seller : unico inconveniente que un seller podra ser vendedor de varios products
     * surga repiticon del mismo : es decir un comprador pueda comopra un producto de un vendedor en 2 dos ocaciones o compra 2 o 3 productos vendidos por mismo vendedor
     * la solucion es la funcion unique() de laravel que no va a permitie que un indentificadorObject de seller no se repita en la coleccion
    */

} // esta es una operacion bastante compleja para la relacion que hay entre modelos
