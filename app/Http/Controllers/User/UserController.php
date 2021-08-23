<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use App\Models\User; // Orm eloquente de laravel



class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     * meto index en caso de existir en algun controller lo que hace listar todos los Recursos : prover al cliente de Front-end los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() // video 51
    {

      $usuarios = User::all();

      return $this->showAll( $usuarios );


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Crear instancia de user
     * este metodo de este End-point , espera campos de un form cliente
     * video : 53
     */

    public function store(Request $request)
    {

       /* Reglas de validacion
        * campos minimos a recibir y validacion
        * nota 1 , V: 53
        * asi verified and admin , nosotros que asignamos estos valores en la instancia
       */
        $rules = [
          'name' => 'required',
          'email' => 'required|email|unique:users', // users refiere a la tabla del modelo user .
          'password' => 'required|min:6|confirmed'  // confirmed es decir debo reciber 1 campo de : password_confirmation : debe coincidir con password para lograr pasar la validacion
        ];

        /* Ejecutar las reglas validar la request , sino laravel dispara un excepcion : luego veremos como manejarla  */
        $this->validate( $request, $rules );

        /* en este nivel  hemos pasado la barrera de la validacion */
        /* Insancia de de Request : datos del form cliente recibido o posteado desde el cliente */
        $campos = $request->all();
        $campos['password'] = bcrypt( $request->password ); // Encryptar password de manera acorde a los requiremientos de la Db . - bcrytpr es un Helpers
        $campos['verified'] = User::USUARIO_NO_VERIFICADO; // debe ser asignado explicitamente por nosotros , y no de acorde al que user envia . nosotros quien indicamos si es verificado o no , por default
        $campos['verification_token'] = User::generarVerificationToken(); // paraque justamente el user verifique su cuenta o correo electronico
        $campos['admin'] = User::USUARIO_REGULAR;  // debe ser asignado explicitamento por nosotros , y no de acorde al que user envia . nosotros quien indicamos  si es admin o no . por default user regular no admin

       /* Operacion de almacenamiento */
       $usuario = User::create($campos);

       /*returno response de 201 - indicando que ya se realizo la operacion del almacenmaiento */
       return $this->showOne($usuario, 201 );


    }



    /**
     * Display the specified resource.
     *  prover un user depende del $id especicado
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * User $user : video 73 : Injeccion de modelos en algunos metodos : si la injeccion no encuetra instancia basada en id de requesta dispara excepcion : parea excepciones ver seccion 15
     */
    public function show( User $user )
    {


        return $this->showOne( $user );

    }


    /**
     * Update the specified resource in storage.
     * Actualizar user  - video 54
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, User $user )  // no usado .debe recibir id params como put - por injeccion de dependencia me saca la instancia del objeto user a actualizar
    {



        $reglas = [ // nota : requirido nada , no obligo a actualizar algun campo en especifico al momento de request por parte del cliente
          'email' => 'email|unique:users,email,' . $user->id, // unico sin incluir el del user , hay casos cambias otros campos , asi no rompemos
          'password' => 'min:6|confirmed',
          'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR, // regla in : los 2 valores que Aceptamos en este caso , verificar valor de admin  de estos 2 posibles valores
        ];

         /* validar la reglas
          * aqui se dispara la Excepcion , que debemos manipular Posteriomente
         */
         $this->validate( $request , $reglas );



        /* Proceso de asignar la actualizacion , campo por campo  */
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ( $request->has('email') && $user->email != $request->email ) { // en caso sea email diferente , eso quiere decir que el email sea no verificado debo generale nuevo token para verified el user
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken(); // este token se generado a user para Fin de verificacion
            $user->email = $request->email;
        }

        if ( $request->has('password') ) {
            $user->password = bcrypt( $request->password );
        }

        if ( $request->has('admin') ) {
           // $this->allowedAdminAction();

            if (!$user->esVerificado()) { // Video 54
               // return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
                return $this->errorResponse( 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409 );
            }

            $user->admin = $request->admin; // esta verificado ... puede ..
        }

        // verificar si el user ha realizado algun tipo de actualizacion : es decir si los valores son lo mismo tanto de la instancia del $user como las llegadas por $request
        if ( !$user->isDirty() ) {
            return $this->errorResponse( 'Se debe especificar al menos un valor diferente para actualizar', 422 );
        }

        /* almacenamiento en Db */
        $user->save();

       // returno modelo con su modificaciones realizadas
       return $this->showOne( $user );


    }

    /**
     * Remove the specified resource from storage.
     *  eleminar instancias que ya existen
      TODO  :
      * es recomendable no eleminar solo al modelo user dar una prop donde manejamos dos valores booleanos como hezimos en node . segun validacion sabemos que la cuenta del user desactivada: es decir eleminada
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)  // v:73 para mas informacion
    {


        $user->delete();

        return $this->showOne( $user );

    }
}
