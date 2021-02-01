<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *Decuelve los productos de un vendedor específico
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

   //Al tener al vendedor asociado directamente a la url puesto que se trata de una operación compuesta, permite controlar que quién esté realizando la operación sea realmente el propietario de ese producto, por ejemplo en el caso de una actualización o de una eliminación o en el de la creación

   //permite crear nuevas instancias de producto asociadas a un vendedor específico

   public function Store(Request $request, User $seller)//que sea user garantiza que un usuario nuevo pueda montar su producto
   {
        $rules = [

            'name'=> 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',

        ];


        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = '1.jpg';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product, 201); 
   }

    /**
     * Update the specified resource in storage.
     *Permite actualizar una instancia existente de un producto de un vendedor específico
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
       $rules = [
           'quantity' => 'integer|min:1',
           'status' => 'in: ' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,
           'image' => 'image',
        ];


        $this->validate($request, $rules);

        $this->verificarVendedor($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',

        ]));

        if($request->has('status')) {
           $product->status = $request->status;
           
           if($product->estaDisponible() && $product->categories()->count() == 0) {
            return $this->errorResponse('Un producto activo debe tener al menos una categoría', 409);
           } 
        }

        if($product->isClean()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller)
    {
        $this->verificarVendedor($seller, $product);//con esta sentencia estamos seguros que el vendedor es el propietario

        $product -> delete();

        return $this->showOne($product);
    }

    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id) {
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
        }
    }
}
