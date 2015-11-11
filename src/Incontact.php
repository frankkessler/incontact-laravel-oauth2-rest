<?php

namespace Frankkessler\Incontact;

use CommerceGuys\Guzzle\Oauth2\Oauth2Client;
use CommerceGuys\Guzzle\Oauth2\GrantType\AuthorizationCode;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use CommerceGuys\Guzzle\Oauth2\Utilities;
use Frankkessler\Incontact\Repositories\TokenRepository;

class Incontact{
    public function __construct($config=null){
        if($config){
            IncontactConfig::setAll($config);
        }
        $this->repository = new TokenRepository;
        $this->token_record = $this->repository->store->getTokenRecord();

        $base_uri = $this->token_record->instance_base_url;

        $this->oauth2Client = new Oauth2Client([
            'base_uri' => $base_uri,
            'auth' => 'oauth2',
        ]);

        $this->oauth2Client->setAccessToken($this->token_record->access_token, $access_token_type='Bearer');
        $this->oauth2Client->setRefreshToken($this->token_record->refresh_token, $refresh_token_type='refresh_token');
        $refresh_token_config = [
            'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
            'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            'refresh_token' => $this->token_record->refresh_token,
            'token_url' =>'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.token_uri'),
            'auth_location' => 'body',
        ];
        $this->oauth2Client->setRefreshTokenGrantType(new RefreshToken($refresh_token_config));
    }

    public function getObject($id, $type){
        return $this->call_api('get','sobjects/'.$type.'/'.$id);
    }

    public function createObject($type, $data){
        return $this->call_api('post','sobjects/'.$type, [
            'http_errors' => false,
            'body' => json_encode($data),
            'headers' => [
                'Content-type' => 'application/json',
            ]
        ]);
    }

    protected function call_api($method, $url, $options=[], $debug_info=[]){
        try{
            if(is_null($options)){
                $options = [];
            }

            if(isset($options['body'])){
               // var_dump($options['body']);
            }

            $options['http_errors'] = false;

            $response = $this->oauth2Client->{$method}($url, $options);
            //var_dump((string)$response->getBody());
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


            if(isset($data) && $data) {
                $this->updateAccessToken($this->oauth2Client->getAccessToken()->getToken());
                return $data;
            }
        }catch(ClientException $e){

        }
        return [];
    }
    protected function updateAccessToken($current_access_token){
        if($current_access_token != $this->token_record->access_token) {
            $this->repository->store->setAccessToken($current_access_token);
        }
    }
}