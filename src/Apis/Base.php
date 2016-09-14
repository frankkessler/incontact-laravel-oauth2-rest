<?php

namespace Frankkessler\Incontact\Apis;

class Base
{
    public function __construct(&$client)
    {
        $this->client = $client;
    }
}
