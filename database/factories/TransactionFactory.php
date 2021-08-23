<?php

namespace Database\Factories;

use App\models\User;
use App\models\Seller;
use App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        /*
         * cualquier id de un user consta de product : es vendedor : es seller
        */
        $vendedor = Seller::has('products')->get()->random();

        /*
         * podria ser cualquier id de user menos el vendedor mismo : no puede ser un user compra su producto
         *
         */
        $comprador = User::all()->except($vendedor->id)->random();

        return [
          'quantity' => $this->faker->numberBetween(1, 3),
          'buyer_id' => $comprador->id,
          'product_id' => $vendedor->products->random()->id, // seller consta de varios products -> accedo a la relacion ->obtengo uno

        ];
    }
}
