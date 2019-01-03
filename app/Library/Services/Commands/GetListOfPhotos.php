<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3.1.19
 * Time: 18.19
 */

namespace App\Library\Services\Commands;


use App\Exceptions\InvalidClientOldException;
use App\Library\Services\Command\CommandInterface;
use App\Library\Services\ResultOfCommand;
use App\Photos;
use Illuminate\Support\Facades\Validator;

class GetListOfPhotos implements CommandInterface
{
    const OPERATION = "gallery";

    function handle(array $options): ResultOfCommand
    {
        if (Validator::make($options, ["newsID" => "required"])->fails()) {
            throw new InvalidClientOldException('gallery', "You should specify news id on your request.");
        }
        $result = (new ResultOfCommand())->setOperation($this::OPERATION);

        $photos = Photos::getList($options['newsID'])->get();

        if ($photos->count()) {
            return $result->setMessage('Photos in gallery: ' . $photos->count())
                ->setStatus(200)
                ->setContent($photos);
        } else {
            //TODO delete from result array content key
            return $result->setMessage('No photos found by this id.')
                ->setStatus(200);
        }
    }
}