<?php

namespace App;

use App\Transaction;
use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
	protected static function boot()//el método boot es normalmente utilizado para construir e inicializar el modelo y por supuesto en este caso lo utilizarémos para indicarle qué scope utilizar-
	{
		parent::boot();//parent:modelo base

		static::addGlobalScope(new BuyerScope);
	}
	//un comprador tiene muchas transacciones
    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }
}
