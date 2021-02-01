<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\getModel;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * Lista de tipos de excepción que no se notifican.
     *
     * @var array
     */
    protected $dontReport = [
        //En esta parte hay una lista de tipos de excepciones que no se notifican, preguntar si es por la versión. Revisar
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Representa una excepción en una respuesta HTTP. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {   //Este condicional permite a postman mostrar en una respuesta json, qué atributos están obteniendo error y cuál es dicho error.
        if($exception instanceof ValidationException) { //Algo que también se va a hacer es manejar directamente las excepciones de tipo validación exception, esto es necesario puesto que actualmente las excepciones de tipo exceptionValidation están siendo manejadas directamente por el método render, sin embargo, como verémos más adelante no podrémos depender únicamente del método render para manejar diferentes tipos de excepciones que pueden surgir en la ejecución de nuestra Api puesto que el método render tiende a mostrar gran detalle de algunas excepciones, lo cual no es adecuado especialmente si ya estamos en etapa de producción y no en etapa de desarrollo en nuestra Api
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        //Este condicional permite arrojar un mensaje en json cuando el usuario que se está buscando no existe en la base de datos 
        if($exception instanceof ModelNotFoundException){
            $modelo = strtolower(class_basename($exception->getModel()));//esta línea no funciona(importé 2 definiciones, no sé si son las correctas. Revisar)
            return $this->errorResponse('No existe ninguna estancia de '.$modelo.' con el id especificado', 404);
        }
        //este condicional controla las excepciones que corresponden a los usuarios que no están autenticados en el sistema
        if ($exception instanceof AuthenticationException) {//los objetos son instancias, 
            return $this->unauthenticated($request, $e); //unauthenticated se modificó en la definición de render. Está ubicado allá no acá.
        }
        //este condicional controla las excepciones relacionadas con la autorización para una respectiva acción
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse ('No posee permisos para ejecutar esta acción', 403); 
        }
        //controla los errores de escritura de la url
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse ('No se encontró la URL especificada', 404); 
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('El método especificado en la petición no es válido', 405); 
        }
        //existen muchísimos tipos de excepciones http que podrían surgir durante la ejecución de la Api, este condicional permite controlar de manera genérica un mensaje para este tipo de excepciones
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->message(), $exception->getStatusCode());
        }
        //Hay algunos usuarios que no se pueden eliminar debido a que están relacionados con otros recursos, este condicional nos permite controlar este tipo de excepciones
        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];

            if($codigo == 1451){
                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque está relacionado con algún otro', 409);
            }
            
        }
        if(config('app.debug')){//Este condicional pregunta si la máquina esta depurada para saber si está en entorno de producción o desarrollo, con el fin de mostrar los dealles de la falla inesperada si estamos en un entorno de desarrollo
           return parent::render($request, $exception);
        }

        //Falla interna de la Api
        return $this->errorResponse('Falla inesperada. Intente luego', 500);

    }

      /**
     * Convierte una excepción de autenticación en una respuesta. Está función de autenticación está en la definición de exceptionHandler y no en nuestro handler como lo enseñan en el curso. Revisar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
                    ? response()->json(['message' => $exception->getMessage()], 401)
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }*/

    /*Crea un objeto de respuesta a partir de la excepción de validación dada.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        return $request->expectsJson()
                    ? $this->invalidJson($request, $e)
                    : $this->invalid($request, $e);
    }*/

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->messages();

        
        return $this->errorResponse($errors, 422);
    }

}

