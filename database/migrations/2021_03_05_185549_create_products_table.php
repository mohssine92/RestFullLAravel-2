<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

   /* ==> Recordemos que el orden en que se establezcan las migraciones es muy importante , puesto que laravel les va a ejecutar de manera secuencial , es decir para hacer migracion de producto tiene que existira la migraciond de user
    porque producto hace referencia a user  */
class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {

            $table->increments('id'); // autoincremental
            $table->string('name');
            $table->string('description', 1000);  // especificar un valor maximo de caracteres de 1000
            $table->integer('quantity')->unsigned(); // entero - no tenga signo : debe ser positivo
            $table->string('status')->default( Product::PRODUCTO_NO_DISPONIBLE );  // por defecto no disponible - Recordar importar la definicion del modelo
            $table->string('image');
            $table->integer('seller_id')->unsigned(); // es id : es clave foreanea - apunta a la tabla users : porque es la cual obtenemos la lista de sellers - herencia - positive id
            $table->timestamps();
            $table->softDeletes();

            /* establecer la calve foreanea que tiene el objeto producto  referiendo al id usuario
             * observacion Model seller no consta de tabla , se herede extiende de userModel .
            */
            $table->foreign('seller_id')->references('id')->on('users');





        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
