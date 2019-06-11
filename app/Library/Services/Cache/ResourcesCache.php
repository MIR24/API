<?php


namespace App\Library\Services\Cache;


use App\Library\Services\Resources\InterfaceRouter;
use Illuminate\Support\Facades\Cache;


class ResourcesCache
{

    /**
     * @var InterfaceRouter
     */
    private $router;

    /**
     * ResourcesCache constructor.
     * @param InterfaceRouter $router
     */
    public function __construct(InterfaceRouter $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $url
     * @param array $params
     * @param int $duration
     * @return mixed
     */
    public function addCache(string $url, array $params, int $duration = 0)
    {

        if (Cache::has($url)) {
            return Cache::get($url);
        }

        $data = $this->router->getResult($params);

        Cache::put($url, $data, $duration);
        return $data;
    }
}