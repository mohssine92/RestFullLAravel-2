<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
//use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $transactions = $product->transactions; // relacion - so no tien transaccion retuna coleccion vacia

        return $this->showAll($transactions);


    }  // obtener las trabsacciones de un product en especifico


}
