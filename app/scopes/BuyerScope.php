<?php


namespace App\Scopes;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;





/* Los Scopes deben implememntar una Interfaz llamada scope
 * y al implementar la interfaz estamos en la obligacion de implementar la funcion como esta definida que returna esta interfaz
 * builder : es el constructur de la consulta - y modelo como tal
*/
class BuyerScope implements Scope
{

	public function apply(Builder $builder, Model $model)
	{

		$builder->has('transactions');


	} // lo que haga esta funcion modificar la consulta tipica del modelo y agregar el has transacaccion , sabemos que el construcor es el primer funccion que se ejecuta en cualquier class ,
      // asi cuando vamos a cosnultar el modelo ya tiene jecutada la relacion de comprador



}
