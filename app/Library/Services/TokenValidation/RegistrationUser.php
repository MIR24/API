<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 11.18
 */

namespace App\Library\Services\TokenValidation;

use App\Exceptions\InvalidOldTokenException;
use App\Exceptions\RestrictedOldException;
use App\Exceptions\ServerOldException;
use App\Library\Services\Command\CommandInterface;
use App\Library\Services\ResultOfCommand;
use App\Library\Services\ResultTokenOfCommand;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RegistrationUser implements CommandInterface
{
    private const OPERATION = 'auth';

    /**
     * @param array $options
     * @return ResultOfCommand
     * @throws InvalidOldTokenException
     * @throws RestrictedOldException
     * @throws ServerOldException
     */
    function handle(array $options): ResultOfCommand
    {

        if ($this->isNotValidUserData($options)) {
            throw new InvalidOldTokenException($this::OPERATION);
        }


        return (new ResultTokenOfCommand)
            ->setOperation($this::OPERATION)
            ->setMessage("Authentication successful.")
            ->setToken($this->getTokenIdByUserData($options))
            ->setStatus(200);
    }


    private function isNotValidUserData(array $options)
    {
        return Validator::make($options,
            [
                'login' => 'required|min:4',
                'password' => 'required|min:4',

            ])->fails();
    }

    /**
     * TODO may be need more
     * @param array $options
     * @return mixed
     * @throws RestrictedOldException
     * @throws ServerOldException
     */
    private function getTokenIdByUserData(array $options)
    {

        if (!Auth::attempt(['name' => $options['login'], 'password' => $options['password']])) {
            throw new RestrictedOldException($this::OPERATION);
        }

        try {
            $res = DB::table('oauth_access_tokens')
                ->where(['user_id' => Auth::user()->id])
                ->limit(1)
                ->first(['id'])->id;

        } catch (\Exception $ex) {
            throw new ServerOldException($this::OPERATION);
        }


        if (!$res) {
            throw new RestrictedOldException($this::OPERATION, 'RESTRICTED. NOT FOUND TOKEN');
        }

        return $res;
    }

}