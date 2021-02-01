<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();

        //return response()->json(['data' => $usuarios], 200); //Es muy importante ser consistentes en la manera en que se retornan respuestas, para los clientes o usuarios que vayan a consumir la apirestful es muy importante contar con un elemento raíz en la respuesta, de esa manera, al identificar ese elemento raíz se puede determinar desde qué punto obtener los datos que se han solicitado. Elemento raíz = data

        //return $usuarios;

        return $this->showAll($usuarios);
    }


    /**
     * Store a newly created resource in storage. Para crear instancia se debe hacer por medio del método POST en postman
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas= [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $reglas);

        $campos = $request->all(); //array con nombre, email y contraseña del usuario
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;
        
        $usuario = User::create($campos);// con create realizo una asignacion masiva  de atributos al array campos

        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     
    public function show($id)
    {
        $usuario = User::findOrFail($id);//findOrFail retorna una respuesta de error si el id que se busca no se encuentra

        return $this->showOne($usuario);
    }*/
    //La inyección implícita de modelos nos permite básicamente resolver la instancia de un modelo a partir de un id recibido en este caso a partir de la url en el método directamente, con esto se evita tener que buscar de manera directa un instancia de ese modelo con el id
    public function show(User $user)//es importante el nombre del parámetro a utilizar aquí, laravel basa su funcionamiento en el nombre del parámetro user para así establecer el valor. No es directamente el parámetro del método si no el nombre del parámetro en la ruta como tal y si el valor que utilizamos es usuario en vez de user, ese parámetro de la ruta no coincidirá y tendríamos problemas
    {

        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //permite actualizar instancias de usuarios ya exitentes, las actualizaciones se realizan por medio del método put o patch de postman con la opcion x-www-form-urlencoded


        $reglas= [
            'email' => 'email|unique:users, email,'. $user->id,
            'password' => 'min:6|confirmed',
            'admin'=> 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this -> validate($request, $reglas);

        if($request->has('name')){
            $user->name = $request->name; //el atributo name será igual al requerido en la petición
        }

        if($request->has('email') && $user->email != $request->email){
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $User->verification_token = User::generarVerificationToken();
                $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password); //revisar
        }

        if($request->has('admin')){
            if(!$user->esVerficado()){
                //return response()->json(['error'=> 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 'code' => 409],409);
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }

            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $user->save();

        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     Me permite eliminar instancias de usuario que ya existen
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        //return ($id);

        //$user = User::findOrFail($id);


        $user -> delete();

        return $this->showOne($user);
    }
}
