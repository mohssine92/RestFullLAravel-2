<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;

class CategoryProductController extends ApiController // ( aqui esta implementado el trait)
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category) // si vez mapa de routes (este metodo requiere identificador para la injeccion implicita del modelo  )
    {
        /* en este caso tengo relacion directa entre entidades ,
         * accedo a la relacion como prop y obtengo las categorias al que pertenece este product
         * recordar un producto pertenece a varias categorias - y una categoria tiene mucho productos
         */
        // dd($category->products);

        $products = $category->products;
        return $this->showAll($products);

    }


}

/* basado en una categoria Obtenemos todos productos pertenecen a la misma
 *
*/


