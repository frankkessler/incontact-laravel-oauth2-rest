<?php

namespace Frankkessler\Incontact;

use CommerceGuys\Guzzle\Oauth2\Oauth2Client;
use CommerceGuys\Guzzle\Oauth2\GrantType\AuthorizationCode;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use CommerceGuys\Guzzle\Oauth2\Utilities;
use Frankkessler\Incontact\Clients\Password;
use Frankkessler\Incontact\Repositories\TokenRepository;

class Incontact{

    public $client;

    protected $config;

    public function __construct($config=null){

        $this->config = $config;

        IncontactConfig::setInitialConfig($config);

        $oauth_config = [];

        if(isset($config['handler'])){
            $oauth_config['handler'] = $config['handler'];
        }

        $this->client = $this->getActiveClient($oauth_config);
    }

    protected function getActiveClient($config=[]){
        $oauth_method = IncontactConfig::get('incontact.oauth.auth_method');
        return $this->{'get'.strtoupper($oauth_method).'Client'}($config);
    }

    protected function getPasswordClient($config=[]){
        return new Password($config);
    }

    /**
     * @param $level
     * @param $message
     * @return mixed|void
     */
    protected function log($level, $message)
    {
        if ($this->config['salesforce.logger'] instanceof \Psr\Log\LoggerInterface && is_callable([$this->config['logger'], $level])) {
            return call_user_func([$this->config['salesforce.logger'], $level], $message);
        } else {
            return;
        }
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