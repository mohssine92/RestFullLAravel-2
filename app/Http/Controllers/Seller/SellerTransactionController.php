<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;

use App\Models\Seller;


class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $Seller)
    {
        $transactions = $Seller->products()
               ->whereHas('transactions') // solo los productos vendidos
               ->with('transactions') // agragar prop transaccion a cada propducto (representa coelccion de Objetos de transaccions que a tenido el producto )
               ->get()
               ->pluck('transactions') // me quedo solamente con las colecciones de transacciones
               ->collapse(); // unir las colecciones en una collecion

        return $this->showAll($transactions);


    }


}

/* obtencion de las transacciones de un vendedor ,
 * informacion de los productos vendidos , cantidas y aquien lo ha vendido
 * por su puesto pude que haya un vendedor que no tiene transacciones asi que se returna una colleccion VACIA
 */
