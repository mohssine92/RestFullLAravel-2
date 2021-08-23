<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;


use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory  /* al extender de una clase mayor puedo hacer uso de metodos esta clase mayor */
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(1),
            'quantity' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
            'image' => $this->faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
            // 'seller_id' => User::inRandomOrder()->first()->id,
            /*
             * tiene que ser id de un seller exista en nuestra base de datos .
             * eso quiere decir que el factory de user debe ejecutarse antes de este factory - asi - poder acceder a la tabla users - y seleccionar un id seller .
             */
            'seller_id' => User::all()->random()->id,
        ];
    }
}
