<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource. Devuelve la lista de vendedores de un comprador
     *Para llegar a un vendedor desde un comprador se tiene que ir a la lista de transacciones de este, luego se tiene que ir a la lista de los productos de cada una de esas transacciones y luego obtener el vendedor de cada uno de esos productos
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')//garantiza que no se repita el vendedor
            ->values();//reorganiza los índices en el orden correcto y elimina aquellos que están vacíos


        return $this->showAll($sellers);
    }

    
}
