<?php

namespace App\Library\Services\Command;


use App\Library\Services\ResultOfCommand;

interface CommandInterface
{
    function handle(array $options): ResultOfCommand;
}
