<?php 

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class SellerScope implements Scope//scope es una interfaz que se necesita
{
	public function apply(Builder $builder, Model $model)
	{
		$builder->has('products');
	}
}

