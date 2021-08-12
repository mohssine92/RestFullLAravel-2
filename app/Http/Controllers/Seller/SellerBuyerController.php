<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;


class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $Seller)
    {
        $buyers = $Seller->products()
            ->whereHas('transactions') // condicion
            ->with('transactions.buyer') // traer prop relacion : su valor puede ser Objeto o coleccion depemde del tipo de relacion
            ->get()
            ->pluck('transactions')
            ->collapse() // como 1 preoductct has many transactions , coalpsamos colecciones en un array
            ->pluck('buyer') // quedo cob la prop buyer
            ->unique() // unicos - delet repetiicon
            ->values(); // organizar - eleminar indexes vacios

        return $this->showAll($buyers);



    }

}
/* Lista de compradores de un vendedor en especifico
 * repito con el conocimiento de estos metodos implementados en esta action en general vamos a poder construir y obtener las relaciones entre cualquier par de modelos practicamente
*/
