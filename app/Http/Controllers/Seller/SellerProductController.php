<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

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




    /** subia de un product relacionado a un seller
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
      ]; // tener en cuenta que esta rechazando algunos images

      $this->validate($request, $rules);

      $campos = $request->all(); // una vez la peticion haya superado las reglas - entoces obtenemos todos los datos de la peticion

      // hacer modificaciones sobre ellas
      $campos['status'] = Product::PRODUCTO_NO_DISPONIBLE; // por defecto al inicio al crear un product sera no dispo -depues permitimos editar ...
      $campos['image'] = $request->image->store(''); //  'a' 2 arg se especifica sistema de archivo por defaul que hemos creadover video 113
      // primer arg es para ubicacion en la carpeta de img , produecto pude crecer y si almacenamos en misma carpeta pueden ser millones de images y eso puede crear problema sistema sera lenta para cargar images es decir
      // la edea es almacenar por semana , cada semana se genera una ubicacion para almacenamiento de archivos
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


        $this->validate($request, $rules); // valifdacion de reglas


        // despues de validar los campos _ psamos a realziar nuestras propias verificaciones
        $this->verificarVendedor($seller, $product);


        // llenar las primeras instancias de la actualizacion usando metodo fill() : al llamar este funcion para asegurar de incluir unicamente los valores definidos en la coleccion de los que llegaron por peticion
        $product->fill($request->only([
          'name',
          'description',
          'quantity',
         /*  'status', */

        ]));



        // permitimos cambio de estado Unicamnete si este producto tiene minimo asociada una categoria
        if ($request->has('status')) { // primero si han en viado el status

           /* la logica aqui es si se cambia estado del producto lo cambiamos sin independemento lo cual sea ,
            * pero si se pone disponible y ese producto no esta asociado a ninguna categoria aun , entonces este cambio de estado disponible No se puede realzar
            * returno err
           */
            $product->status = $request->status;  // modificar estado de manera inicial


            if($product->estaDisponible() && $product->categories()->count() == 0 ) { // 0 segnifica coleccion vacia -no consta de cartegoria o categorias
                return $this->errorResponse('Un producto activo debe tener al menos una categoría', 409); //409 : codigo de conflicto
            }
            //return $this->showOne($product);

        }

        if ($request->hasFile('image')) { //115
            Storage::delete($product->image);

            $product->image = $request->image->store('');
        }



        if ($product->isClean()) {  //verificar si se ha realizado alguna modificacion sobre esta instancia Product

            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);

        } // vamos a notar al igual la actualizacion se realiza cuando tenemos img , aunque no con la misma img porque es bastante dificl saber si el archivo es el mismop o no . 115
          // lo mas importante archivo se elemina y se crea otro nuevo precticamente con la misma imagen



        // grabar tal instancia
        $product->save();

        return $this->showOne($product);




    }


    /** asugurar id el vendedor que se especifique en url sea id del vendedor de ese product
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller , Product $product)
    {

       $this->verificarVendedor($seller, $product); // verificar si el vendedor es el propitario

       // eleminacion img del servidor antes de eleminar producto especifico de la db
       Storage::delete($product->image);//esta facade nos permitira interectuar directamente con systema de archivos de laravel - solo especificamos nombre de archivo - hemos configurado sustema de archivos por defecto 114


       $product->delete();

       return $this->showOne($product);

       /* recordar estamos eleminando archivo img de manera difinitiva
        * pero producto no - estamos usando softdelete
          no deberia eleminar de manera difinitiva archivo a menos que la instancia sera eleminada de manera definitiva :
          posteriormente resolvemos el caso proceder la eliminacion tanto archivo como la instancia coo tal
       */
    }



    /* si el metodo se repita en diferentes acciones - sabemos que no es buen practica ir copiando y pegando codigo
       por eso se ha nacido este metodo , usada en delet y update
    */
    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id ) {
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');

        } // dispara una excepcion de esta manera evitamos agrgar nuevo condicional verificando resultado de esa funcion (refiereo resutrnar respues y luego respuesta json) lo cual no riene sentido
          //- disparamos exception de tipo hhtp excepcion
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
