<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $filllable = [
        'quantity',
        'buyer_id',
        'product_id', 
    ];
    //una transaccion pertenece a un comprador y pertenece a un producto

    public function buyer()
    {
    	return $this->belongsTo(Buyer::class);
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
