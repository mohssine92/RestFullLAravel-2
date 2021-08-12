<?php
/* ==>  aqui procedemos creacion de tabla users del modelo user y modificaciones entre otros funcionalidades ... */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');    // autoIncremental
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken(); /* basicamente el token que permitira mantener activa la session de un usuario especialmente en la aplicacion web  */
            $table->string('verified')->default( User::USUARIO_NO_VERIFICADO ); // campo si el user es verificado o no  - valor por default a insertar
            $table->string('verification_token')->nullable();  // no todos users van a tener ver_tok en algun momento asi podria ser nul este campo
            $table->string('admin')->default(User::USUARIO_REGULAR);
            $table->timestamps();  // fecha creacion  -  fecha actualizacion
            $table->softDeletes(); // fecha de eleminacion del modelo (en caso que se haya eleminado ) : seccion 17 : para mas informacion
        });
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}


