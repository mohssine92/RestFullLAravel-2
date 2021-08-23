<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Transaction;
use App\Models\User;
use App\Scopes\BuyerScope;

/*
 * al extender de user enmediatamente herede su estructura , por lo tanto no requiere tablas o migraciones especificamnete dedicadas a el simplemente haran uso de tabla creada para user .
 *  no necesita attributes de manera especifica puesto que lo esta extiendo los de manera dirtecta del modelo user por medio de la herencia
*/
class Buyer extends User // => herede su estructura db : atrributes de su modelo asi -  puesto que .. no necesito importar definicion de model
{
    use HasFactory;


    // metodo protegido y estatico : boot() se usa para construir y inicializar el modelo : especialmente en este caso lo usamos para indicar al modelo que scopes a utulizar - v74
    protected static function boot()
	{
		parent::boot(); // primero llamamos al boot del modelo padre : es decir del modelo base


        // estamos usando operador static puesto que estamos dentro de metodo static (pasamos instancia del scope)
        // para hacer refrencia a un metodo propio de la class es preferible usar de este operador static como tal
		static::addGlobalScope(new BuyerScope);


	}// echo esto : cada vez se hace consulta al modelo Buyer : va aplicar este scope el que indica añadiendo una restreiccion : el que dice solo se obtiene user has transaccion




    /* Relacion entro los modelos 38
     *  relacion de uno a mucho : es decir un comprador es capaz de comprar muchas veces : es capas de hacer muchas transacciones .
    */
     public function transactions()
     {
        return $this->hasMany(Transaction::class);
     }

}
