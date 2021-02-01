<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *Devuelve la lista de todas categorías en las cuales un comprador ha realizado compras
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()//unifica una serie de listas en una única lista
            ->unique('id')//garantiza que no se repita el vendedor
            ->values();//reorganiza los índices en el orden correcto y elimina aquellos que están vacíos


        return $this->showAll($categories);
    }

   
}



