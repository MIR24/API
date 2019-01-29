<?php

namespace App\Http\Middleware;

use App\Library\Services\TokenValidation\TokenValidation;
use Closure;

class APIToken
{

    private  $validation;

    /**
     * APIToken constructor.
     * @param TokenValidation $validation
     */
    public function __construct(TokenValidation $validation)
    {
        $this->validation=$validation;
    }


    /**
     * @param $request
     * @param Closure $next
     * @param mixed $errorsType  configuration for validation errors types @see TokenValidation
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \App\Exceptions\RestrictedOldException
     * @throws \App\Exceptions\UploadException
     */
    public function handle($request, Closure $next,$errorsType=false)
    {
        if($request->get('request')==='auth'){
            return $next($request);
        }
        if($this->validation->isValid($request,$errorsType?false:true)){
            return $next($request);
        }
        return response()->json(["message"=>'error','status'=>'404'],200);
    }
}
