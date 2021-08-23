<?php




namespace Database\Seeders;


// Modelos
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;



use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;




class DatabaseSeeder extends Seeder  /* como esta classe  DatabaseSeeder extiende de esta clase  Seeder puedo utulizara todos los metodos de la clase Seeder   */
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* desactivar los eventos relacionados a un modelo en provider . una causa es voy a mandar muchos coreos electronicos al momento de seedear el modelo de user
           es buena idea desactivarlo por cada uno de nuestros modelos 121
        */
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();



        /* evitar caller en el problema de claves Foreaneas al momento de borra  */
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        /* lo que hace truncar es decir  borra registro de la tabla No borra tabla , referimos limpiar antes de insertar de migrar datos  */
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
         /* =>para tabla pivote puesto que no tenemos un modelo directo , hacemos acceso a este por medio de la TABLA  utulizando la facede de DB , */
        DB::table('category_product')->truncate();




        /* el orden logico mas importante - hay seeders necesitan dependencia de prop de otros modelos : otros seeders */
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(TransactionSeeder::class);





    }

}
