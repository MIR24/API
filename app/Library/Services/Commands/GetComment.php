<?php

namespace App\Library\Services\Commands;


use App\Comment;
use App\Exceptions\InvalidClientOldException;
use App\Library\Components\EloquentOptions\CommentOptions;
use App\Library\Services\ResultOfCommand;
use Illuminate\Support\Facades\Validator;


class GetComment implements CommandInterface
{
    private const OPERATION = "comment";

    /**
     * @param array $options
     * @return ResultOfCommand
     * @throws InvalidClientOldException
     */
    public function handle(array $options): ResultOfCommand
    {
        $result = (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setStatus(200);

        $validator = Validator::make($options, ["entityID" => "required|integer", "type" => "required|integer"]);
        $validator->setCustomMessages([
            "entityID.required" => "The entityID field is required.",
            "entityID.integer" => "The entityID must be an integer."
        ]);
        if ($validator->fails()) {
            throw new InvalidClientOldException($this::OPERATION, $validator->errors()->first());
        }
        $commentOptions = (new CommentOptions())->initFromArray($options);

        $comments = Comment::GetComments($commentOptions)->get();
        if (count($comments)) {
            return $result
                ->setContent($comments)
                ->setMessage(sprintf(
//                    "%d of %d comments shown.",  # TODO require calculated $commentOptions->getTotal()
                    "%d comments shown.",
                    count($comments)
//                    $options["total"]
                ));
        } else {
            return $result->setMessage("No comments for this news.");
        }
    }
}
