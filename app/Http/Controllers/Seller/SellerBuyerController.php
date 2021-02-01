<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *Devuelve la lista de los compradores de un vendedor específico 
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
         $buyers = $seller->products()
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }

    
}
