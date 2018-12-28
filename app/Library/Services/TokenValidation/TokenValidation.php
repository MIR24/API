<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 9.31
 */

namespace App\Library\Services\TokenValidation;


use App\Exceptions\InvalidOldTokenException;
use Illuminate\Http\Request;

class TokenValidation
{
    /**
     * TODO create actual validation token
     * @param Request $request
     * @return bool
     */
    public function isValid(Request $request):bool {
      //  throw new InvalidOldTokenException($request->get('request'));
        return true;
    }
}