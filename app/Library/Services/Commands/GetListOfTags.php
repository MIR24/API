<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4.1.19
 * Time: 9.37
 */

namespace App\Library\Services\Commands;


use App\Exceptions\InvalidClientOldException;
use App\Library\Services\ResultOfCommand;
use App\Tags;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GetListOfTags implements CommandInterface
{

    const OPERATION = "tags";

    function handle(array $options): ResultOfCommand
    {

        if (Validator::make($options, [
            'tagsID' => 'array',
            'sortType' => Rule::in(['actual', 'top']),
            'page' => 'numeric'
        ])->fails()) {
            throw new InvalidClientOldException(self::OPERATION);
        }

        $result = (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setStatus(200);

        if (isset($options['tagsID'])) {

            return $result->setContent(Tags::GetListByTagsID($options['tagsID'])->get());

        } else if (isset($options['sortType']) && isset($options['page'])) {

            return $result->setContent(Tags::GetListTags($options['sortType'], $options['page'])->get()->each(function ($item) {
                unset($item['count']);
            }));

        } else {
            throw new InvalidClientOldException(self::OPERATION);
        }
    }


}