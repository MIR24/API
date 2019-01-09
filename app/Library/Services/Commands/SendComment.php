<?php

namespace App\Library\Services\Commands;


use App\Comment;
use App\Exceptions\InvalidClientOldException;
use App\Exceptions\ServerOldException;
use App\Library\Services\ResultOfCommand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class SendComment implements CommandInterface
{
    private const OPERATION = "comment";

    /**
     * @param array $options
     * @return ResultOfCommand
     * @throws InvalidClientOldException
     * @throws ServerOldException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(array $options): ResultOfCommand
    {
        $validator = Validator::make($options, [
            "name" => ["required", "string", "min:1"],
            "profile" => ["required", "string", "min:1"],
            "email" => "sometimes|required|email",
            "entityID" => ["required", "integer", "min:1"],
            "text" => ["required"],
            "type" => ["required"],
        ]);

        $filteredOptions = null;

        if ($validator->fails()) {
            throw new InvalidClientOldException($this::OPERATION, $validator->errors()->first());
        } else {
            $filteredOptions = $validator->validated();
        }

        $filteredOptions["entity_id"] = $filteredOptions["entityID"];
        unset($filteredOptions["entityID"]);
        $filteredOptions["type_id"] = $filteredOptions["type"];
        unset($filteredOptions["type"]);

        $comment = new Comment($filteredOptions);
        $comment->time = new \DateTime("now");

        if (!$comment->save()) {
            throw new ServerOldException($this::OPERATION, "Comment not saved");
        }

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent(null)
            ->setMessage("Comment added.")
            ->setStatus(200);
    }
}
