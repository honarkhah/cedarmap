<?php
namespace Cedar;

use Illuminate\Support\Facades\Facade;

class NamaaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cedar';
    }
}