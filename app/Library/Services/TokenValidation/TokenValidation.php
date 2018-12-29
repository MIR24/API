<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 9.31
 */

namespace App\Library\Services\TokenValidation;


use App\Exceptions\RestrictedOldException;
use Illuminate\Http\Request;
use Laravel\Passport\Token;


class TokenValidation
{
    /**
     * @param Request $request
     * @return bool
     * @throws RestrictedOldException
     */
    public function isValid(Request $request):bool {
        $token= Token::find($request->get('token'));
        if(!$token){
            throw new RestrictedOldException($request->get('operation'));
        }
        return true;
    }
}