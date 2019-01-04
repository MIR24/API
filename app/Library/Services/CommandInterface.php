<?php

namespace App\Library\Services\Commands;


use App\Library\Services\ResultOfCommand;

interface CommandInterface
{
    function handle(array $options): ResultOfCommand;
}
