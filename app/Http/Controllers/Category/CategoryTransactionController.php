<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;


class CategoryTransactionController extends ApiController
{

    /** 95
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $Category)
    {

      $transactions = $Category->products()
               ->whereHas('transactions') // condicion : solo los productos que tienen por lo menos solo una transaccion .(asi evitamos Transacciones vacios)
               ->with('transactions') // existe la posiblidad de que varias transacciones este vacias : producto todavia no tiene ningu venta : producto no tiene aun ningn transaccion asociada
               ->get()
               ->pluck('transactions') // retuna solo prop transaccions
               ->collapse(); // como un producto pude tener multiples transacciones : Obtenemos coelleciones de transacciones de cada producto : collaps me adjunta todo en una coleccion

      //dd($transactions);
      return $this->showAll($transactions);

    }

}
/* ahora el caso un poco diferente . atraves de categori ObtenemosProductos bien , no tenemos la certeza que exista un transaction para cierto producto
 * posible escenario : que un producto no pose de trasaccion : es decir no se ha vendido todavia
 */
