<?php

namespace Frankkessler\Incontact;

use CommerceGuys\Guzzle\Oauth2\Oauth2Client;
use CommerceGuys\Guzzle\Oauth2\GrantType\AuthorizationCode;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use CommerceGuys\Guzzle\Oauth2\Utilities;
use Frankkessler\Incontact\Clients\Password;
use Frankkessler\Incontact\Repositories\TokenRepository;

class Incontact{
    public function __construct($config=null){
        if($config){
            IncontactConfig::setAll($config);
        }
        $this->repository = new TokenRepository;
        $this->token_record = $this->repository->store->getTokenRecord();

        $this->client = $this->getActiveClient();
    }

    protected function getActiveClient(){
        $oauth_method = IncontactConfig::get('incontact.oauth.auth_method');
        return $this->{'get'.strtoupper($oauth_method).'Client'}();
    }

    protected function getPasswordClient(){
        return new Password();
    }

    public function __call($method, $args)
    {
        if(!isset($this->{$method})){
            $class =  "\\Frankkessler\\Incontact\\Apis\\".$method;
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