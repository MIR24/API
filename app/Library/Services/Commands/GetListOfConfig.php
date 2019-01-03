<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3.1.19
 * Time: 16.19
 */

namespace App\Library\Services\Commands;


use App\Library\Services\Command\CommandInterface;
use App\Library\Services\ResultOfCommand;
use Illuminate\Support\Facades\Config;

class GetListOfConfig implements CommandInterface
{

    function handle(array $options): ResultOfCommand
    {
        //TODO may be need save this config into database?
        //more information into config file /config/api_images.php
        $config=Config::get('api_images');

        return (new ResultOfCommand())
            ->setOperation('config')
            ->setContent($config)
            ->setMessage('Config of site')
            ->setStatus(200);
    }
}