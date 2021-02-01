<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO ='1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

//Mutador y accesor, son métodos que se implementan para la modificación de un atributo y para acceder a dicho valor. En el contexto de las aplicaciones desarrolladas elaboradas en laravel un mutador se utiliza para modificar el valor original de un atributo antes de hacer la insersión en la base de datos y el accesor se utiliza para modificar el valor de un atributo dado después de haberlo obtenido de la base de datos

    public function setNameAttribute($valor) // Mutador. Esta función obliga a que el nombre al digitarlo esté en minúscula
    {
        $this->attributes['name'] = strtolower($valor); 
    }

    public function getNameAttribute($valor)//Accesor. Esta función le pone mayúscula a cada palabra
    {
        return ucwords($valor);
    }

    public function setEmailAttribute($valor)//Mutador. Esta función obliga a escribir en minúscula
    {
        $this->attributes['email'] = strtolower($valor);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function esVerficado()
    {
        return $this-> verified ==User::USUARIO_VERIFICADO;
    }

    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

/*/el método es estático puesto que no requerimos directamente de una instancia de usuario para poder generar dicho token de verificación*/
    
    public static function generarVerificationToken()
    {
        return (Str::random(40)) ; //return str_random(40); //revisar
    }
}
/*/de manera particular el modelo user en este caso no posee relaciones de manera directa con ningún otro modelo puesto que sus relaciones se ven especificadas a través de los modelos seller y buyer puesto que estos se heredan de él*/