<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;


class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $Seller)
    {
        $categories = $Seller->products()
              ->with('categories')
              ->get()
              ->pluck('categories')
              ->collapse() // colapsar colecciones
              ->unique('id') // sea unic sin param o defin que queremos difirenciarlas unicamente exclusivamente por id
              ->values();

        return $this->showAll($categories);



    }


}
/* Obtener la lista de cateorias a las que pertenecen los productos de un vendedor
 *
*/
