<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     * tabla pivote - ayuda a laravel resolver la relacion de muchos a muchos entre : dos modelos
     * en tabla pivote no es necesario id ni timestamps : puesto no vamos a tener instancias o recursos directamente de la tabla sino se va a utulizar esta tabla por parte de laravel
     * como de forma transparente para nosotros
     * @return void
     */
    public function up()
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
             /* siendo ambos claves Foreaneas
              * enteros y sin signo . ids autoIncrementales positivos
              * vamos a decir son claves foreaneas y apuntan atablas corespondientes
             */

            $table->integer('category_id')->unsigned();
            $table->integer('product_id')->unsigned();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('product_id')->references('id')->on('products');




        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_product');
    }
}
