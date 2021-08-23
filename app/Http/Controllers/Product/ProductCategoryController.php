<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product )
    {

      $categories = $product->categories; // relacion dirtecta

      return $this->showAll($categories);


    } // Obtener categorias de un especifica instancia de un product





    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
      /* Al momento de trabajar con relaciones de mucho a mucho efecto de metodos:
       * sync() : sustituye la lista de categorias existentes por la categoria que hemos especificado : no es lo que necesitamos en este caso
       * attach() : este metodo agraga categoria si borra lalista anterior pero si agregamos la misma categoria la duplica lo cual no es lo que necesitamos
       * syncWithoutDetaching : lo que hara agregar la nueva categoria , sin eleminar las anteriores y no repita la misma categoria
       */

       $product->categories()->syncWithoutDetaching([$category->id]);

       return $this->showAll($product->categories);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product , Category $category )
    {
      /* categories(): usamos metodo que returna la relacion no directamente la propiedad
       * si se cumple esta condicion es decir no existe la relacion de esa categoria con tal product
       */

      if (!$product->categories()->find($category->id)) {

        return $this->errorResponse('La categoría especificada no es una categoría de este producto', 404);

      }


      // proceder de eliminacion de esta relacion

      $product->categories()->detach([$category->id]);

      return $this->showAll($product->categories); // returno estado actual de estas categorias relacion despues de de eleminar una categoria especificada relacionada a tal producto



    }// objetivo eleminar una categoria a un producto : es decir este instancia de ese producto ya no existe en tal categoria
}

