<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     * un vendedor es cualquier user que tenga al menos un producto asociado a el : es decir cosnta de un producto a Vender
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* es vendedor pero puede comprar de otro vendedor esta posiblida de tenerlo con dos perfiles seller y buyer : puesto que consta de un product es vendedor */
        $vendedores = Seller::has('products')->get();

        return $this->showAll( $vendedores );

    }


    public function show(Seller $seller) //si no existe el objeto en la coleccion ... dispra excepcion 404 - usamos Injeccion implicita - applicando scope Global al modelo
    {


       return $this->showOne( $seller );

    }

}
