<?php

namespace App\Http\Controllers\Category;

//use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /** seccion 18
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


      $categories = Category::all();


     // recordemos para usar metodos generalizadas para respuesta de nuestra api debemos extender de apiController que esta usando el trait el que consta de estas funciones
     return $this->showAll( $categories );

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // $this->allowedAdminAction();

       //reglas de valifacion
        $rules = [

          'name' => 'required',
          'description' => 'required',
        ];
        //TODO no debe permitir creacion de la misma categoria validacion a añadir es similira de email


        $this->validate($request, $rules); // en caso de .. dispara excepcion de validacion


        // Grabar en db
        $category = Category::create($request->all());

        // instancia de la categoria creada
        return $this->showOne($category, 201);
    }

    /** give one categori per id
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     * en caso no existira la instancia por id , esta Accion no se ejecutara , y estamos controlando la Excepcion en el archvo Handler: como hemos hecho en clases anteriores
     */
    public function show(Category $category)
    {
        return $this->showOne($category);

    }



    /**
     * Update the specified resource in storage.
     * actualizar una instancia existente de una categoria
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     *
     */
    public function update(Request $request, Category $category) // la peticion requiere un id
    {
        // v 81 actualizar la instancia de category por id , - metodo fill reciba los valores que vamos a actualizar en la instancia recibida por id
        $category->fill($request->only([
         // attr que queremos rellenar - otro diff sera denegado
          'name',
          'description'
        ])); // la actualizacion ser en el objeto de instancia : objeto enmemoria no en db - requiere save


        // una vez se ejecuta la instruccuin de arriba debemos verificar si se ha cambiado algo o no debido que el user no manda los campos en la peticion etccc - debo lanzar una excepcion
        // o manda valores identicos al anteriores tambien debo notificar el err

        if ($category->isClean()) { // si la instancia de db no ha cambiado

            return $this->errorResponse('Debe especificar al menos un valor diferente para actualizar', 422);

        }

        //en caso se asegura que se han cambiado valores de la instancia en memoria grabamos en db
        $category->save();

        return $this->showOne($category);





    }

    /** V82
     * Remove the specified resource from storage.
     * requiere id - para Injeccion de la instacia del modelo
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->showOne($category);
    }
}
