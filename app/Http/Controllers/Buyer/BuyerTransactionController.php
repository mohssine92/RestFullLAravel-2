<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer; // impotacion de la definicion del modelo : porque al momento de crear el controlador le hemos indicado la injeccion implicita del mismo modelo
use Illuminate\Http\Request;

class BuyerTransactionController extends ApiController   //Controller
{
    /** v89
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer) // Injeccion implicita
    {
      // la obtencion es sensilla puesto que tenemos en el modelo Buyer un metodo de relacion directa

       $transactions = $buyer->transactions;

       return $this->showAll( $transactions );


    }


} // de echo tenemos una operacion compleje entre el modelo Buyer y el modelo Transaction
