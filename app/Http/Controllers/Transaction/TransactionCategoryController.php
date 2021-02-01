<?php

namespace App\Http\Controllers\Transaction;


use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource. Devuelve las categorías del producto involucardo en una transacción específica
     *Una vez se conoce la transacción sigue acceder a las categorías, pero se sabe que no hay una relación directa entre transaction y category, pero también sabemos que una transacción tiene un producto y que ese producto tiene una lista de categorías, así que básicamente las categorías del producto involucrado en la transacción son las categorías de esa transacción misma
     * @return \Illuminate\Http\Response
     */
    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->categories;//Se obtiene la lista de categorías, primero obteniendo la relación hacia el producto de esta transacción, y una vez tenemos el producto se puede acceder a las categorías de este producto.

        return $this->showAll($categories);
    }

    
}
