<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $fillable = [
    	'name',
        'description',
    ];

    protected $hidden = [
        'pivot'
    ];
//una categoria tiene una relacion de muchos a muchos con productos
    public function products()
    {
    	return $this->belongsToMany(Product::class);
    }

}
