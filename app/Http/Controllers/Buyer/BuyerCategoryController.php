<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;


class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)  //Buyer $buyer
    {
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories') // aqui como la relacion es de mucho a mucho obtendre colecciones asique
        ->collapse() // collapse metodo de laravel me ajunta las listas (las colecciones ) en una
        ->unique('id') // despues elemino la seguna repiticion del id ...
        ->values(); // este metodo elemina el index vacio : ver BuyeeSelelrController esta bien esplicado

        //dd($categories);
        return $this->showAll($categories);

    }
    /* en esta operacion sabemos que un product pertenece a una categoria o categorias , pues quiero obtener la relacion de Buyer y category
     * asi el comprador sabra que categoria o categorias la que pertenece el producto que ha comprado atraves de una transaction
    */

}


