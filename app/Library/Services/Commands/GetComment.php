<?php

namespace App\Library\Services\Commands;


use App\Comment;
use App\Library\Components\EloquentOptions\CommentOptions;
use App\Library\Services\ResultOfCommand;


class GetComment implements CommandInterface
{
    private const OPERATION = "comment";

    public function handle(array $options): ResultOfCommand
    {
        $result = (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setStatus(200);

        # TODO required entityID and type?
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
