<?php

use App\Models\Admin;
use App\Models\Worker;
use Core\Debug\Debugger;
use Core\Router\Router;
use Lib\Authentication\Auth;

if (!function_exists('d')) {
    function dd(): void
    {
        Debugger::dd(...func_get_args());
    }
}

if (!function_exists('route')) {
    /**
     * @param string $name
     * @param mixed[] $params
     * @return string
     */
    function route(string $name, $params = []): string
    {
        return Router::getInstance()->getRoutePathByName($name, $params);
    }
}

if (!function_exists('generateImageUniqName')) {

    function generateImageUniqName(string $name): string
    {
        $name = str_replace(' ', '', $name);
        return uniqid() . '-' . basename($name);
    }
}

if (!function_exists('assetsDir')) {
    function assetsDir(): string
    {
        return __DIR__ . '/../../public/assets/';
    }
}

if (!function_exists('currentAdmin')) {
    function currentAdmin(): Admin
    {
        return Auth::user('admin');
    }

}

if (!function_exists('currentWorker')) {
    function currentWorker(): Worker
    {
        return Auth::user('worker');
    }

}
