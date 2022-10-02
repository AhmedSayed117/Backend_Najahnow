<?php

namespace App\Exceptions;

use App\Http\Traits\GeneralTrait;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use GeneralTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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

//    public function report(Exception $e)
//    {
//        parent::report($e);
//    }
//    
    public function render($request, Exception $e)
    {
        // dd($exception);
        if ($e instanceof AuthenticationException)
            return $this->returnError('E099', 400, "unauthenticated user");
        else if ($e instanceof ModelNotFoundException)
            return $this->returnError('E099', 400, $e->getMessage());
        else if($e instanceof NotFoundHttpException)
            return $this->returnError('E099', 400, 'Route not found!');
        return $this->returnError('E099', 400, $e->getMessage());
    }
}
