<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.12.18
 * Time: 11.18
 */

namespace App\Library\Services\TokenValidation;

use App\Library\Services\Command\CommandInterface;
use App\Library\Services\ResultOfCommand;
use App\Library\Services\ResultTokenOfCommand;

class RegistrationUser implements CommandInterface
{

    /**
     * TODO create normal registration
     * @param $options
     * @return ResultOfCommand
     */
    function handle(array $options): ResultOfCommand
    {
        return (new ResultTokenOfCommand)
            ->setOperation('auth')
            ->setMessage("Authentication successful.")
            ->setToken("token_id")
            ->setStatus(200);
    }

}