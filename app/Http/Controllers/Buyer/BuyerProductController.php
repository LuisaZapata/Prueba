<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource. Devuelve  los productos que un comprador ha obtenido. Se podría pensar que solo se tiene que acceder a las transacciones y luego a los productos de esas transacciones y de hecho esta sería la lógica a seguir, sin embargo es un poco problemático hacerlo a través de estas relaciones directas puesto que entre buyer y transaction la relación es de uno a muchos y para obtener los productos de cada una de esas transacciones no se puede hacer directamente a través de la relación de éstas puesto que al acceder a la relación se va a obtener una colección y no una instancia de transaction por lo tanto no se tendría una manera directa de acceder al producto
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')//con transaction() se accede al query builder y no  a la relación, en este caso ya no se estaría obteniendo una colección si no un builder que permite agregar diferentes restricciones a la consulta. El with sirve para utilizar el higher loading y acceder a las relaciones, este puede recibir una serie de relaciones pero en este caso se va a utilizar la relación que se necesita traer, entonces se traería el producto de cada una de esas transacciones
            ->get()//y se debe obtener por el método get
            ->pluck('product');//este método permite trabajar u operar directamente sobre la colección, e indicar que se quiere solo una parte de esa colección completa

        return $this->showAll($products);
    }

    
}
