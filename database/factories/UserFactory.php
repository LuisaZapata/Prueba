<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Seller;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Factories. son basicamente funciones que nos permiten crear instancias de diferentes modelos o de diferentes recursos por medio de datos falsos, por defecto en laravel hacen uso de una librería faker de php que me permite crear estos datos falsos
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password, REVISAR clases de la funcion que crea la clave!
        'remember_token' => Str::random(10),
        'verified'=> $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token'=> $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description'=> $faker->paragraph(1),
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description'=> $faker->paragraph(1),
        'quantity'=> $faker->numberBetween(1, 10),
        'status' =>$faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
        'image' => $faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        // 'seller_id' => User::inRandomOrder()-> first()->id,
        'seller_id' => User::all()->random()->id,

        ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    
    $vendedor = Seller::has('products')->get()->random();
    $comprador = User::all()->except($vendedor->id)->random();

    return [
        'quantity'=> $faker->numberBetween(1, 3),
        'buyer_id' => $comprador->id,
        'product_id' => $vendedor->products->random()->id,
    ];
});