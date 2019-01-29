<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 9.31
 */

namespace App\Library\Services\TokenValidation;


use App\Exceptions\RestrictedOldException;
use App\Exceptions\UploadException;
use Illuminate\Http\Request;
use Laravel\Passport\Token;


class TokenValidation
{

    /**
     * TODO may be need chang if-else to switch-case
     * @param Request $request
     * @param mixed $typeError if true use instanceof  @see RestrictedOldException if false @see UploadException
     * @return bool
     * @throws RestrictedOldException
     * @throws UploadException
     */
    public function isValid(Request $request,$typeError):bool {
        if($typeError){
            return $this->isOldValid($request);
        }else{
            return $this->UploadTokenIsValid($request);
        }
    }


    protected function isOldValid(Request $request):bool {
        $token= Token::find($request->get('token'));
        if(!$token){
            throw new RestrictedOldException($request->get('request')??"");
        }
        return true;
    }

    public function UploadTokenIsValid(Request $request):bool{
        $token= Token::find($request->get('token'));
        if(!$token){
            throw new UploadException('Wrong token.',403);
        }
        return true;
    }
}