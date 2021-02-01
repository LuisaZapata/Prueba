<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *las migraciones permiten crear la estructura de las tablas
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');//autoincremental, aumenta de uno en uno automáticamente    
            $table->string('name');
            $table->string('email')->unique(); //email debe ser un valor único en toda la base de datos
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken(); //token que permitirá tener activa la sesión de un usuario especialmente en la aplicación web
            $table->string('verified')->default(User::USUARIO_NO_VERIFICADO);//campo de si el usuario es verificado o no
            $table->string('verification_token')->nullable();//no todos los usuarios van a tener un token de verificacion en algún momento así que ese campo podría ser NULL o vacío
            $table->string('admin')->default(User::USUARIO_REGULAR);//Restricción
            $table->timestamps(); //fecha de creación y fecha de actualización
            $table->softDeletes();// guarda la fecha de "borrado" (ocultamiento) de un registro
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
