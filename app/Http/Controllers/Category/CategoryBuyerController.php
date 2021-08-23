<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;


class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $Category)
    {

        $buyers = $Category->products()
            ->whereHas('transactions') // solo productos vendidos
            ->with('transactions.buyer') // traer prop relacion compradores
            ->get()
            ->pluck('transactions') // quedamos solo con colecciones de taransacciones
            ->collapse() // unificar las colecciones en una coleccion - facil de acceder
            ->pluck('buyer') // quedamos solo con prop buyer
            ->unique()  // eleminar la repiticion del identificador Object buyer (deja indexes vacios)
            ->values();  // organizar array eleminando los indexes vacios

        return $this->showAll($buyers); // nuestro metodo se tarait , forma estadarizada para responder a request por parte externa



    }

}
/* 96 - los compradores que han efectuando una compra en un acategoria especifica
 * relacion indirecta de entidades : category->product->transaction->buyer
 *
 * con estos metodos podemos resolver cualquier relacion como sea en cualquier proyecto que estemos desarollando
*/
