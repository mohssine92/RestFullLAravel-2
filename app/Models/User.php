<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;



class User extends Authenticatable
{



    //softDeletes : V 78 , 77
    use HasFactory, Notifiable , softDeletes;

    /*
     * de manera particular este modelo no pose de relaciones directas con ninguno de los modelos , sino atraves de los modelos que lo heredan : seller - buyer
    */

    /* decir de una manera explicita la tabla de este modelo
     * evitar problemas con models sellers y buyers , al momento de jecutar las migraciones . 500
     * asi como seller y buyer - heredan user asi heredan el attribute $table y lo usaran de manera implicita .
    */
    protected $table = 'users';


    /*  decirle el attributes como tal debe ser tratado como una fecha
     recuerda que para modelos que extienden de user no es neceseriamente porque extienden las funcionalidades directamente
     de echo hacer estos el modelo tien la capacida de usar Soft Deleting
     asi se elemino Objeto por id laravel no lo va a considerar al momento de consultar la lista users - pero si voy a db lo encuentro con fecha de eleminacion(seccion17 mas infrormcion)
     veer video 77 : REFRESH MEMORIA HUMANA
     */
    protected $dates = ['deleted_at'];


    // usadas para el attribute verify
    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    // usuadas para la prop admin - dijo el profesor luego veremos por que es siempre mejor usar estos valores como strings , sean booleanos o number : siempre debe ir como string
    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';



    /* mutadores y Accesores al momento de almacenamiento o recuperamiento : ver video 60 para refrescar memoria
     * Mutadores : set : establecer
    */
     public function setNameAttribute ( $valor ) {
       $this->attributes['name'] = strtolower( $valor );
     } // momento de establecer antes de insertar le aplica ....al prop : atrib del this model

     public function setEmailAttribute ( $valor ) {
        $this->attributes['email'] = strtolower( $valor );
     } // otro mutador para correo electronico


     /* Accesores : video 60
      * get despes de obtener de db modifica
     */
     public function getNameAttribute ( $valor ) {
       // return ucfirst( $valor ); // solo primer palabra
          return ucwords( $valor );
    }  // se transforma el valor sin necesida de modificarlo , poner primer letra en mayuscula de la Composicion del name



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified', // hacemos uso de constantes para determinar si un user esta verificado o no .
        'verification_token', // para verificar justamente su correo electronico atraves de un codigo de verificacion
        'admin', // usamos constantes para saber si el user autenticado es admin o no - en otros caso trabajamos con roles

    ];


    /**
     * The attributes that should be hidden for arrays.
     * es decir cuando laravel convierta el modelo user en una respuesta json , lo que hace lo convierta primero en un array y luego dicho array lo transforma en un formato json
     * por lo cual cualquier attribute incluido en en $hidden sera occultado en nuestras respuestas json _> en nuestra saliada
     * @var array
     */

    protected $hidden = [
       'password',
       'remember_token',// basicamente cuando user inicia session por medio de front-end es decir por medio de interfaz grafica - y tilda la opcion de recordarme : esta parte ayudara si un user debe mantenerse con session activa o no .
       'verification_token', // nadie puede acceder ... paraque luego validarlo de manera incorrecta :_ esta validacion debe realizarse unicamente desde el correo electronico del propitario de esta cuenta del user autenticado
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'email_verified_at' => 'datetime',
    ];


  /*
   * para saber si un user esta verificado o no
  */
   public function esVerificado()
   {
      return $this->verified == User::USUARIO_VERIFICADO;
   }


   /*
   *  para saber si un user es administrador o no
   */
   public function esAdministrador()
   {
      return $this->admin == User::USUARIO_ADMINISTRADOR;
   }


   /*
   *  me permit obtener un token de verificacion generado automaticamente
   */
   public static function generarVerificationToken()
   {
       return Str::random(40);  // recomendado desde 25 adelante , en este caso 40
   }



}
