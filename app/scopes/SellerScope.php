<?php

namespace App\Scopes;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;



/* ver Buyer scopp hay comentarios espilcan bien o ver videos 74 , 75
 *
*/
class SellerScope implements Scope


{
	public function apply(Builder $builder, Model $model)
	{

		$builder->has('products');

	}

}
