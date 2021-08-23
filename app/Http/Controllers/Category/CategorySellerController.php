<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;

use App\Models\Category;

class CategorySellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $Category)
    {
        $sellers = $Category->products()
              ->with('seller') // solicitar que cada uno de en de lalista de los productos vengan con seller
              ->get()
              ->pluck('seller') // dejame solo elemento  seller
              ->unique() // borra los objetos repetetivos : resulta sus indices quedan vacios
              ->values(); // organiza el array - cada index tiene valor vacio lo elemina
       //dd($sellers);
       return $this->showAll($sellers); // generalizar la respuesta en json standar


    }


}
/* obtener lista de vendedores para una categoria especifica
 * la relacion entre entidades es indirecta : category->product->seller
 * unique : existe la posiblida de que una categoria tiene productos y varios productos pertenecen al mismo seller
 */
