<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use Illuminate\Http\Request;



/* Operaciones para Model Buyer
 * Video 56 para mas detalles
 *
*/
class BuyerController extends ApiController
{

    /**
     * Display a listing of the resource.
     * mostrar todos compradores que hay en el systema
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // compradores , es decir solo users que tengan transacciones de products , Recordar todo user esta almacenado en tabla users , herencia etc ...
       // has() recibe una Relacion que tenga ese Modelo
       $compradores = Buyer::has('transactions')->get();

       return $this->showAll( $compradores );

    }



    /**
     * Display the specified resource.
     * Obtener instancia de un comprador siempre que si si existe por supuesto
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer) // si insatcia no fount - dispar excepcion  _ 74 : Resolviendo la Injeccion de Buyer Usando Global scoppe en el model Buyer
    {


       return $this->showOne( $buyer );


    }


}
