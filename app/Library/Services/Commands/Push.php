<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.1.19
 * Time: 12.45
 */

namespace App\Library\Services\Commands;


use App\Exceptions\InvalidClientOldException;
use App\Exceptions\ServerOldException;
use App\Library\Services\ResultOfCommand;
use App\PushToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class Push implements CommandInterface
{
    private const OPERATION = "push";

    function handle(array $options): ResultOfCommand
    {
        $validator = Validator::make($options, [
            'token' => 'required',
            'type' =>['required',Rule::in(['GCM', 'APN','gcm','apn'])]

        ]);
        if ($validator->fails()) {
            if ($validator->errors()->has('token')) {
                throw new InvalidClientOldException(self::OPERATION, 'Token are null or empty.');
            }
            if ($validator->errors()->has('type')) {
                throw new InvalidClientOldException(self::OPERATION, 'Type must be GCM or APN.');
            }
        }

       $options['type']= strtoupper($options['type']);

        try {
            PushToken::firstOrCreate($options);
        } catch (\Exception $exception) {
            throw new ServerOldException(self::OPERATION,env("APP_DEBUG")?$exception->getMessage():"SERVER ERROR");
        }

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setMessage("PUSH-token registered correctly.")
            ->setStatus(200);


    }


}