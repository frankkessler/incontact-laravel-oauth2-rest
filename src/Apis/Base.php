<?php

namespace Frankkessler\Incontact\Apis;

class Base
{
    protected $client;

    public function __construct(&$client)
    {
        $this->client = $client;
    }
}
