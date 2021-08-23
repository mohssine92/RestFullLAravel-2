<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer; // impotacion de la definicion del modelo : porque al momento de crear el controlador le hemos indicado la injeccion implicita del mismo modelo
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
       // un Buyer has Transactions and one Transaction blongsTo One Product (relacion inderecta) : Obtener todos productos comprados por one Buyer
       // ver video 90 , accedo a la relacion obtengo coleccion , no es el caso ,
       // el caso incuir otra relacion existente : acceder a query bilder ...  el caso acceder a la funcion no a la relacion como tal

       $products = $buyer->transactions()->with('product')
                   ->get()
                   ->pluck('product'); // indicar que queremos obtener solo una parte de esa coleccio completa

        //dd($products); // noto que  un sa transaccio de venta permite solo un producto , puedo implementar que una transaccion tener relacion con productos


       return $this->showAll( $products );



    } // obtencion de productos relacionados con un comprador en especifico de forma indirecta usando las relaciones

}
