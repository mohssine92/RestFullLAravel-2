<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/* ==> sintaxis para generar factory indicandole cual es el modelo que se encarga => php artisan make:factory CategoryFactory --model=Category   */

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             // ids ,la  db se encarga de incertar de manera autoincremental -secuencialmente por cada uno de estos factories incertados
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(1),

        ];
    }
}
