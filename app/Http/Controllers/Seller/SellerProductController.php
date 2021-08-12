<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;




/* el que hace la peticion de crear product tiene que tener autorizacion sufuciente o que sea mismo seller:vendedor
 *
*/
class SellerProductController extends ApiController
{
    /** 101
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $Seller)
    {

      $products = $Seller->products; // relacion directa - obtener productos de un seller en especifico
      return $this->showAll($products);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * 102
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $Seller ) // $request : nos brinda acceso a la informacion relacionada a dicha peticion , tambien debe recibir el vendedor - ver comentarios para entender esta injeccion implicita
    {
      /* crera nuevas instancias de Product asociadas a un seller en especifico
       * injeccion implicita de user , segun la logica implementada un seller es user  tiene 1 productoal menos publicado , asi si un user quiere publicar un producto por primera vez , en el momento instantaneo todavia
       * no tiene  producto asi no es seller , por facilitar injectamos de user que es cualquier user
      */

      // reglas de validacion
      $rules = [
        'name' => 'required',
        'description' => 'required',
        'quantity' => 'required|integer|min:1', // numero intero , tener un valor por lo menos 1
        'image' => 'required|image', // debe ser por supuesto una imagen
      ];

      $this->validate($request, $rules);

      $campos = $request->all(); // una vez la peticion haya superado las reglas - entoces obtenemos todos los datos de la peticion

      // hacer modificaciones sobre ellas
      $campos['status'] = Product::PRODUCTO_NO_DISPONIBLE; // por defecto al inicio al crear un product sera no dispo -depues permitimos editar ...
      $campos['image'] = '1.jpg';//$request->image->store('');
      $campos['seller_id'] = $Seller->id; // $user : sera un seller despues de la subida del primer product


      $product = Product::create($campos);



      return $this->showOne($product, 201); // como es operacion de creacion , respondemos con codigo 201

      // NB: puedo crear mismo producto , solo la diff sera el id del mismo


      /*  esta parte de procesar las imagenes : es recomendable uso de cloudinary: se supene esta apirest ful comunicar con server de node que es asyncrono y el server de node va recibiendo request y hace response
          asi server node recibe objeto mandar la refrecia de la imagen  a este apirestful y la imagen la suba en servidor de cloudunary
      */

    }



    /* Update accion : actualizar una instancia de un product existente de un seller en especifico
     * en el proceso ed actualizacion vamos a asegurarnos de que el seller que se especifico en url que sea verdademente propitario  de dicho producto
     */




    /** 103 - Actualizar Producto de un especifico Seller
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller , Product $product){

        $rules = [
          'quantity' => 'integer|min:1',//  numero entero , minimo 1
          'status' => 'in:' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE, // los dos estado que permita recibir esta prop
          'image' => 'image', // tiene que ser una imagen , si la recibimos ,no es required
        ];


        $this->validate($request, $rules);


        // despues de validar los campos _ psamos a realziar nuestras propias verificaciones
        if ($seller->id != $product->seller_id) {
            return $this->errorResponse('El vendedor especificado no es el vendedor real del product', 422);
        }


        // llenar las primeras instancias de la actualizacion usando metodo fill() : al llamar este funcion para asegurar de incluir unicamente los valore recibidos en la peticion usamo only() metod de la petiicion
        $product->fill($request->only([
          'name',
          'description',
          'quantity',

        ]));


        // permitimos cambio de estado Unicamnete si este producto tiene minimo asociada una categoria
        if ($request->has('status')) { // primero si han en viado el status

           /* la logica aqui es si se cambia estado del producto lo cambiamos sin independemento lo cual sea ,
            * pero si se pone disponible y ese producto no esta asociado a ninguna categoria aun , entonces este cambio de estado disponible No se puede realzar
            * returno err
           */
            $product->status = $request->status;  // modificar estado de manera inicial

            if($product->estaDiponible() && $product->categories()->count() == 0 ) { // 0 segnifica coleccion vacia -no consta de cartegoria o categorias
                return $this->errorResponse('Un producto activo debe tener al menos una categoría', 409); //409 : codigo de conflicto
            }

        }



        if ($product->isClean()) {  //verificar si se ha realizado alguna modificacion sobre esta instancia Product

            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);

        }



        // grabar tal instancia
        $product->save();

        return $this->showOne($product);






    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller)
    {
        //
    }






    /* en este caso puede ser usada para obtener un producto en especifico de un vendedor
     * pues lo obtengo de una manera sencilla el controller -r de product - accion show pasandois del mismo
     */
    //public function show(Seller $seller)

     /* eleminados por que no voy a usar formularios de manera direca
      *
      */
    //public function edit(Seller $seller)


}
