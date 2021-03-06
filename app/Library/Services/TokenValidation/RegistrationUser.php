<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 11.18
 */

namespace App\Library\Services\TokenValidation;

use App\Exceptions\InvalidClientOldException;
use App\Exceptions\RestrictedOldException;
use App\Exceptions\ServerOldException;
use App\Library\Services\Commands\CommandInterface;
use App\Library\Services\ResultOfCommand;
use App\Library\Services\ResultTokenOfCommand;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RegistrationUser implements CommandInterface
{
    private const OPERATION = 'auth';

    /**
     * @param array $options
     * @return ResultOfCommand
     * @throws InvalidClientOldException
     * @throws RestrictedOldException
     * @throws ServerOldException
     */
    function handle(array $options): ResultOfCommand
    {
        if (array_key_exists('pass', $options) && !array_key_exists('password', $options)) {
            $options['password'] = $options['pass'];
        }

        if ($this->isNotValidUserData($options)) {
            throw new InvalidClientOldException($this::OPERATION);
        }


        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setMessage("Authentication successful.")
            ->setContent(['token'=>$this->getTokenIdByUserData($options)])
            ->setStatus(200);
    }


    private function isNotValidUserData(array $options)
    {
        return Validator::make($options,
            [
                'login' => 'required|min:' . Config::get("auth.MIN_LENGHT_OF_LOGIN"),
                'password' => 'required|min:' . Config::get("auth.MIN_LENGHT_OF_PASSWORD"),
            ])->fails();
    }

    /**
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
            $queryToToken = DB::table('oauth_access_tokens')
                ->where(['user_id' => Auth::user()->id])
                ->limit(1);

            $res = $queryToToken->first(['id']);

            if ($res === null) {
                Auth::user()->createToken('Personal Access Token')->token->save();

                $res = $queryToToken->first(['id']);
            }
            $res = $res->id;

        } catch (\Exception $ex) {
            throw new ServerOldException($this::OPERATION);
        }


        if (!$res) {
            throw new RestrictedOldException($this::OPERATION, 'RESTRICTED. NOT FOUND TOKEN');
        }

        return $res;
    }

}