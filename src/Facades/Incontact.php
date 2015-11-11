<?php

namespace Frankkessler\Incontact\Facades;

use Illuminate\Support\Facades\Facade;

class Incontact extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'incontact'; }

}
