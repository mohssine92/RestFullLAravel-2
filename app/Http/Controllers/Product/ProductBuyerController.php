<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;

class ProductBuyerController extends ApiController
{
    /** php artisan make:controller Product/ProductBuyerController -r -m Product
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $buyers = $product->transactions()
              ->with('buyer')
              ->get()
              ->pluck('buyer')
              ->unique('id')
              ->values();

        return $this->showAll($buyers);




    } // obtener lista de compradores de  un producto en especifico


}
