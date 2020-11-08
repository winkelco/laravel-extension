<?php

namespace WinkelCo\LaravelExtension\Facades;

class Hook extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        return 'hook';
    }
}
