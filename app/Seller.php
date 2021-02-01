<?php

namespace App;

use App\Product;
use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
	protected static function boot()//el método boot es normalmente utilizado para construir e inicializar el modelo y por supuesto en este caso lo utilizarémos para indicarle qué scope utilizar-
	{
		parent::boot();//parent:modelo base

		static::addGlobalScope(new SellerScope);
	}
	//un vendedor tiene muchos productos
    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
