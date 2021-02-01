<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
	private function sucessResponse($data, $code)
	{
		return response()->json($data, $code); //método privado encargado de construir respuestas satisfactorias data=información a retornar, code=código de la respuesta
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code); //método para errores
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		return $this->sucessResponse(['data'=>$collection], $code); //método encargado de mostrar una respuesta con múltiples elementos, es decir, una colección (lista de usuarios)
	}

	protected function showOne(Model $instance, $code = 200)
	{
		//return 'hola';
		return $this->sucessResponse(['data'=>$instance], $code); //método encargado de mostrar una única respuesta, es decir, una instancia 
	}
}

//tendrá el código necesario para construir las respuestas de nuestra Api