<?php

namespace App;

use App\Seller;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;


	const PRODUCTO_DISPONIBLE = 'disponible';
	const PRODUCTO_NO_DISPONIBLE = 'no disponible';


    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',    
        //el modelo que pertenece al otro es quien lleva la clase foranea, en este caso el producto pertenece al vendedor    
    ];

    protected $hidden = [
        'pivot'
    ];

    public function estaDisponible()
    {
    	return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    //un producto pertenece a un vendedor
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    //un producto posee muchas transacciones
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    //relacion muchos a muchos con categoría
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}

