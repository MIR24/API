<?php

namespace App\Library\Services\Commands;


use App\Library\Services\ResultOfCommand;


class GetComment implements CommandInterface
{
    private const OPERATION = "comment";

    public function handle(array $options): ResultOfCommand
    {
        # TODO
// CommentOptions options
//     = gson.fromJson(clientRequest.getOptions().toString(),
//         CommentOptions.class);
// ArrayList<Comment> comments = tracker.getComments(options);
// if (comments == null || comments.isEmpty()) {
//     serverResponse.setStatus(200);
//     serverResponse.setMessage("No comments for this news.");
// } else {
//     serverResponse.setStatus(200);
//     serverResponse.setMessage(comments.size() + " of " + options.getTotal()
//         + " comments shown.");
//     serverResponse.setContent(comments);
// }

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
// TODO            ->setContent()
// TODO            ->setMessage(sprintf("", ))
            ->setStatus(200);
    }
}
