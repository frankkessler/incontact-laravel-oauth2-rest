<?php

namespace Frankkessler\Incontact;

use Frankkessler\Incontact\Clients\Password;

class Incontact
{
    public $client;

    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config;

        IncontactConfig::setInitialConfig($config);

        $this->client = $this->getActiveClient($config);
    }

    protected function getActiveClient($config = [])
    {
        $oauth_method = IncontactConfig::get('incontact.oauth.auth_method');

        return $this->{'get'.strtoupper($oauth_method).'Client'}($config);
    }

    protected function getPasswordClient($config = [])
    {
        return new Password($config);
    }

    public function __call($method, $args)
    {
        if (!isset($this->{$method})) {
            $class = '\\Frankkessler\\Incontact\\Apis\\'.$method;
            $this->{$method} = new $class($this->client);
        }

        return $this->{$method};
    }

    //laravel 5.1 allows for static calling of facades so although this looks wrong and is wrong, it's the only way to get magic methods to work with facades
    public static function __callStatic($method, $args)
    {
        return $this->__call($method, $args);
    }
}
