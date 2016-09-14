<?php

namespace Frankkessler\Incontact\Clients;

use CommerceGuys\Guzzle\Oauth2\Oauth2Client;
use CommerceGuys\Guzzle\Oauth2\GrantType\PasswordCredentials;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use Frankkessler\Incontact\Repositories\TokenRepository;
use Frankkessler\Incontact\IncontactConfig;

class Password extends Base implements OauthClientInterface
{
    public function returnClientGrantTypeClass()
    {
        $config = [
            'username' => IncontactConfig::get('incontact.oauth.username'),
            'password' => IncontactConfig::get('incontact.oauth.password'),
            'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
            'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            'token_url' =>'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.token_uri'),
            'body_type' => 'json',

        ];
        return new PasswordCredentials($config);
    }

    public function call_api($method, $url, $options=[], $debug_info=[])
    {
        $response = $this->_call_api($method, $url, $options, $debug_info);
        if($response && isset($response['http_status']) && $response['http_status'] == 401){

        }elseif($response){
            return $response;
        }
    }
}