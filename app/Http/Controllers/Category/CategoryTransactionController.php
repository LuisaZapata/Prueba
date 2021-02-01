<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *Devuelve la lista de transacciones que se han efectuado para una categoría específica
     
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions')//No se tiene la certeza de que existe una transacción para un producto, es posible que un producto específico no se haya vendido aún y por ende no tenga transacciones. Para ello se utiliza el método whereHas, este método garantiza que los productos que se están obteniendo hasta ese punto son única y exclusicamente aquellos que ya tienen asociada al menos una transacción
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();
            

        return $this->showAll($transactions);
    }

}
