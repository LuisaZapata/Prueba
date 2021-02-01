<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); //Establece la verificación de las claves foráneas en cero, es decir, desactivado

        User::truncate(); //funcion para borrar o limpiar la tabla antes de ejecutar los datos falsos
        Category::truncate();
        Product::Truncate();
        Transaction::Truncate();
        DB::table('category_product')->truncate();

        $cantidadUsuarios = 1000;
        $cantidadCategorias = 30;
        $cantidadProdctos = 1000;
        $cantidadTransacciones = 1000;

        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategorias)->create();

        factory(Product::class, $cantidadTransacciones)->create()->each(
        	function ($producto) {

        	    $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id'); //pluck, me permite traer sólo el campo 'id' de la tabla
        	    $producto->categories()->attach($categorias);
        	}
        );

        factory(Transaction::class, $cantidadTransacciones)->create();

    }
}
