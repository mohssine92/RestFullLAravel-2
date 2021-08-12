<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        /* ->each()  para cada instancia se ejecuta el siguiente
         * al insertar objeto producto sera returnado objeto producto con su id -> por encadenamiento de metodos lo obtenemos como arg en funcion anonyma
         * genera la asociacion de relacion mucho a mucho se usa attach
        */
        Product::factory(1000)->create()->each(

          function ($producto) {

            // obtener coleccion de objetos de categorias aleatoriamente - luego plug nos retuern coleccion solo de sus ids - seran los asociados a dicho producto
            $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');

            $producto->categories()->attach($categorias);

		  }


        );







    }
}

