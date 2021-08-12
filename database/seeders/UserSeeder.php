<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;       /* no se puede aplicar use antes de namespace */

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::factory(1000)->create();

    }
}
