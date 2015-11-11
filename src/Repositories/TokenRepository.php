<?php

namespace Frankkessler\Incontact\Repositories;

use Frankkessler\Incontact\IncontactConfig;
use Frankkessler\Incontact\Repositories\Eloquent\TokenEloquentRepository;

class TokenRepository
{
    function __construct($config=[])
    {
        $this->store = $this->setStore($config);
    }

    /**
     * @param array $config
     * @return TokenRepositoryInterface
     */

    function setStore($config=[])
    {
        $store_name = IncontactConfig::get('incontact.storage_type');
        return $this->{'create' . ucfirst($store_name) . 'Driver'}($config);
    }

    function createEloquentDriver($config=[])
    {
        return new TokenEloquentRepository;
    }
}