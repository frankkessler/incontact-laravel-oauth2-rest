<?php

namespace Frankkessler\Incontact\Clients;

use CommerceGuys\Guzzle\Oauth2\Oauth2Client;
use CommerceGuys\Guzzle\Oauth2\GrantType\GrantTypeInterface;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshTokenGrantTypeInterface;
use CommerceGuys\Guzzle\Oauth2\AccessToken;
use CommerceGuys\Guzzle\Oauth2\Utilities;
use Frankkessler\Incontact\Repositories\TokenRepository;
use Frankkessler\Incontact\IncontactConfig;
use CommerceGuys\Guzzle\Oauth2\Exceptions\InvalidGrantException;
use GuzzleHttp\Psr7\Request;
use Exception;

class Base
{
    protected $oauth2Client;

    public function __construct($config=[])
    {
        $this->setClient($config);
    }

    public function getAccessToken()
    {
        $oauth2Client = new Oauth2Client();

        $grant_type = $this->returnClientGrantTypeClass();

        if($grant_type instanceof GrantTypeInterface) {
            $oauth2Client->setGrantType($grant_type);
        }

        $access_token = $oauth2Client->getAccessToken();

        if($access_token instanceof AccessToken) {
            $repository = new TokenRepository;
            $repository->store->setTokenRecord($access_token);
            return $access_token;
        }

        return null;
    }

    public function returnClientGrantTypeClass()
    {
        return null;
    }

    public function returnClientRefreshGrantTypeClass($refresh_token=null)
    {
        if(!$refresh_token) {
            $repository = new TokenRepository;
            $token_record = $repository->store->getTokenRecord();
            $refresh_token = $token_record->refresh_token;
        }

        $refresh_token_config = [
            'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
            'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            'refresh_token' => $refresh_token,
            'token_url' =>'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.token_uri'),
            'body_type' => 'json',
        ];
        return new RefreshToken($refresh_token_config);
    }

    protected function setClient($config=[])
    {
        $repository = new TokenRepository;
        $token_record = null;

        if(!$base_uri = IncontactConfig::get('incontact.base_uri')){

            $token_record = $repository->store->getTokenRecord();

            $base_uri = $token_record->instance_base_url;
        }


        $client_config = [
            'base_uri' => $base_uri,
            'auth' => 'oauth2',
        ];

        //allow for override of default oauth2 handler
        if (isset($config['handler'])) {
            $client_config['handler'] = $config['handler'];
        }

        $this->oauth2Client = new Oauth2Client($client_config);

        //If access_token or refresh_token are NOT supplied through constructor, pull them from the repository
        if (!IncontactConfig::get('incontact.oauth.access_token') || !IncontactConfig::get('incontact.oauth.refresh_token')) {
            if(!$token_record){
                $token_record = $repository->store->getTokenRecord();
            }

            IncontactConfig::set('incontact.oauth.access_token', $token_record->access_token);
            IncontactConfig::set('incontact.oauth.refresh_token', $token_record->refresh_token);
            IncontactConfig::set('incontact.oauth.expires', $token_record->expires);
        }

        $access_token = IncontactConfig::get('incontact.oauth.access_token');
        $refresh_token = IncontactConfig::get('incontact.oauth.refresh_token');
        $expires = IncontactConfig::get('incontact.oauth.expires');

        //Set access token and refresh token in Guzzle oauth client
        $this->oauth2Client->setAccessToken($access_token, $access_token_type = 'Bearer', $expires);
        $this->oauth2Client->setRefreshToken($refresh_token);

        $grant_type = $this->returnClientGrantTypeClass();

        if($grant_type instanceof GrantTypeInterface) {
            $this->oauth2Client->setGrantType($grant_type);
        }

        $refresh_grant_type = $this->returnClientRefreshGrantTypeClass($refresh_token);

        if($refresh_grant_type instanceof RefreshTokenGrantTypeInterface){
            $this->oauth2Client->setRefreshToken($refresh_token);
            $this->oauth2Client->setRefreshTokenGrantType($refresh_grant_type);
        }
    }

    protected function _call_api($method, $url, $options=[], $debug_info=[], $try=1){
        $data = [];
        try{
            //$this->setClient();

            //function can be run twice with same input, so check to make sure the url hasn't already been set
            if(!str_contains($url,'services/'.IncontactConfig::get('incontact.api_version'))){
                $url = 'services/'.IncontactConfig::get('incontact.api_version').'/'.$url;
            }

            if(is_null($options)){
                $options = [];
            }

            //return html and error code without exception so they can be sorted below
            $options['http_errors'] = false;

            //required for inContact API to work correctly
            $options['headers']['Accept'] = "*/*";
            $options['headers']['User-Agent'] = NULL;

            $response = $this->oauth2Client->{$method}($url, $options);

            $response_code = $response->getStatusCode();

            if($response_code == 200) {
                $data = json_decode((string)$response->getBody(), true);
            }elseif($response_code == 201){
                $data = json_decode((string)$response->getBody(), true);
                $data['operation'] = 'create';
                if(isset($data['id'])){
                    $data['Id'] = $data['id'];
                }
                unset($data['id']);
            }elseif($response_code == 204){
                if(strtolower($method)=='delete'){
                    $data = [
                        'success' => true,
                        'operation' => 'delete',
                    ];
                }else{
                    $data = [
                        'success' => true,
                        'operation' => 'update',
                    ];
                }

            }elseif($response_code == 400){
                $data = json_decode((string)$response->getBody(), true);
                $data = current($data);
                if(!$data){
                    $data['message_string'] = (string)$response->getBody();
                }
                $data['http_status'] = $response_code;
                $data['success'] = false;
                $data = array_merge($debug_info,$data);

            }else{
                $data = json_decode((string)$response->getBody(), true);
                if(!$data){
                    $data['message_string'] = (string)$response->getBody();
                }
                $data['http_status'] = $response_code;
                $data['success'] = false;
                $data = array_merge($debug_info,$data);
            }

            if(isset($data) && $data  && isset($data['success']) && $data['success']) {
                $this->updateAccessToken($this->oauth2Client->getAccessToken()->getToken());
            }
        }catch(InvalidGrantException $e){
            if($try < 2) {
                //if first InvalidGrantException, try one more time after requesting a new token
                $this->getAccessToken();
                $try = 2;
                return $this->_call_api($method, $url, $options, $debug_info, $try);
            }

        }catch(Exception $e){
            //debug failures
        }
        return $data;
    }
    protected function updateAccessToken($current_access_token){
        if($current_access_token != $this->token_record->access_token) {
            $this->repository->store->setAccessToken($current_access_token);
        }
    }

    public function __call($method, $args)
    {
        $url = isset($args[0])?$args[0]:'';
        $options = isset($args[1])?$args[1]:[];
        $debug_info = isset($args[2])?$args[2]:[];
        return $this->_call_api($method, $url, $options, $debug_info);
    }
}