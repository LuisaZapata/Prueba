<?php 

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
//Esta clase permite utilizar la inyección de modelos en buyer teniendo en cuenta la condición de que un comprador es un usuario que tiene aunque sea una transacción
class BuyerScope implements Scope//scope es una interfaz que se necesita
{
	public function apply(Builder $builder, Model $model)//aply es la función que aplica el scope modificando la consulta del modelo y agregando el has transcantions, builder el constructor de la consulta
	{
		$builder->has('transactions');
	}
}

