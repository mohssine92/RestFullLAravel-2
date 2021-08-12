<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Product;
use App\Models\User;
use App\Scopes\SellerScope;

/*
 * al extender de user enmediatamente herede su estructura , por lo tanto no requiere tablas o migraciones especificamnete dedicadas a el simplemente haran uso de tabla creada para user .
 *  no necesita attributes de manera especifica puesto que lo esta extiendo los de manera dirtecta del modelo user por medio de la herencia
*/
class Seller extends User // => herede su estructura db : atrributes de su modelo asi -  puesto que .. no necesito importar definicion de model
{
    use HasFactory;


    protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new SellerScope);
	}// v 75


    /*
     * relacion de uno a muchos - un seller tiene de 1 a muchos productos a vender
    */
    public function products()
    {
    	return $this->hasMany( Product::class );
    }

}
