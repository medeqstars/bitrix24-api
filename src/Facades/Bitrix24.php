<?php

namespace Medeq\Bitrix24\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Bitrix24
 * @package Medeq\Bitrix24\Facades
 * @method static array|null call($path, $params = [])
 * @method static array|null batch(string $path, array $params)
 */
class Bitrix24 extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Medeq\Bitrix24\Bitrix24::class;
    }
}
