<?php

namespace Frankkessler\Incontact\Clients;

use Exception;
use Frankkessler\Guzzle\Oauth2\AccessToken;
use Frankkessler\Guzzle\Oauth2\GrantType\GrantTypeInterface;
use Frankkessler\Guzzle\Oauth2\GrantType\RefreshToken;
use Frankkessler\Guzzle\Oauth2\GrantType\RefreshTokenGrantTypeInterface;
use Frankkessler\Guzzle\Oauth2\Oauth2Client;
use Frankkessler\Incontact\IncontactConfig;
use Frankkessler\Incontact\Repositories\TokenRepository;
use GuzzleHttp\Psr7\Response;

class Base
{
    protected $oauth2Client;

    protected $config;

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->setClient();
    }

    /**
     * @deprecated
     *
     * @codeCoverageIgnore
     *
     * @return AccessToken|null
     */
    public function getAccessToken()
    {
        $oauth2Client = new Oauth2Client();

        $grant_type = $this->returnClientGrantTypeClass();

        if ($grant_type instanceof GrantTypeInterface) {
            $oauth2Client->setGrantType($grant_type);
        }

        $access_token = $oauth2Client->getAccessToken();

        if ($access_token instanceof AccessToken) {
            $repository = new TokenRepository();
            $repository->store->setTokenRecord($access_token);

            return $access_token;
        }
    }

    public function returnClientGrantTypeClass()
    {
    }

    public function returnClientRefreshGrantTypeClass($refresh_token)
    {
        $refresh_token_config = [
            'client_id'     => IncontactConfig::get('incontact.oauth.consumer_token'),
            'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            'refresh_token' => $refresh_token,
            'token_url'     => 'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.token_uri'),
            'body_type'     => 'json',
        ];

        return new RefreshToken($refresh_token_config);
    }

    protected function setClient()
    {
        $config = $this->config;

        $repository = new TokenRepository();
        $token_record = null;

        if (!$base_uri = IncontactConfig::get('incontact.base_uri')) {
            $token_record = $repository->store->getTokenRecord();

            $base_uri = $token_record->instance_base_url;
        }


        $client_config = [
            'base_uri' => $base_uri,
            'auth'     => 'oauth2',
        ];

        //allow for override of default oauth2 handler
        if (isset($config['handler'])) {
            $client_config['handler'] = $config['handler'];
        }

        $this->oauth2Client = new Oauth2Client($client_config);

        //If access_token or refresh_token are NOT supplied through constructor, pull them from the repository
        if (!IncontactConfig::get('incontact.oauth.access_token') || !IncontactConfig::get('incontact.oauth.refresh_token')) {
            if (!$token_record) {
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

        if ($grant_type instanceof GrantTypeInterface) {
            $this->oauth2Client->setGrantType($grant_type);
        }

        $refresh_grant_type = $this->returnClientRefreshGrantTypeClass($refresh_token);

        if ($refresh_grant_type instanceof RefreshTokenGrantTypeInterface) {
            $this->oauth2Client->setRefreshToken($refresh_token);
            $this->oauth2Client->setRefreshTokenGrantType($refresh_grant_type);
        }
    }

    protected function _call_api($method, $url, $options = [], $debug_info = [], $try = 1)
    {
        $data = [];
        try {
            //$this->setClient();

            //function can be run twice with same input, so check to make sure the url hasn't already been set
            if (!str_contains($url, 'services/'.IncontactConfig::get('incontact.api_version'))) {
                $url = 'services/'.IncontactConfig::get('incontact.api_version').'/'.$url;
            }

            if (is_null($options)) {
                $options = [];
            }

            //return html and error code without exception so they can be sorted below
            $options['http_errors'] = false;

            //required for inContact API to work correctly
            $options['headers']['Accept'] = '*/*';
            $options['headers']['User-Agent'] = null;

            $response = $this->oauth2Client->{$method}($url, $options);

            /* @var Response $response */

            $response_code = $response->getStatusCode();

            if ($response_code == 200) {
                $data = json_decode((string) $response->getBody(), true);
            } else {
                $data = json_decode((string) $response->getBody(), true);
                if (!$data) {
                    $data['message_string'] = (string) $response->getBody();
                }
                $data['http_status'] = $response_code;
                $data = array_merge($debug_info, $data);
                $this->log('error', 'Incontact - '.json_encode($data));
            }
        } catch (Exception $e) {
            //debug failures
            $this->log('error', 'Incontact - '.$e->getMessage().' - '.$e->getFile().':'.$e->getLine());
        }

        return $data;
    }

    /**
     * @param $level
     * @param $message
     *
     * @return mixed|void
     */
    protected function log($level, $message)
    {
        if (isset($this->config['incontact.logger']) && $this->config['incontact.logger'] instanceof \Psr\Log\LoggerInterface && is_callable([$this->config['incontact.logger'], $level])) {
            return call_user_func([$this->config['incontact.logger'], $level], $message);
        } else {
            return;
        }
    }

    protected function updateAccessToken($current_access_token)
    {
        if ($current_access_token != $this->token_record->access_token) {
            $this->repository->store->setAccessToken($current_access_token);
        }
    }

    public function __call($method, $args)
    {
        $url = isset($args[0]) ? $args[0] : '';
        $options = isset($args[1]) ? $args[1] : [];
        $debug_info = isset($args[2]) ? $args[2] : [];

        return $this->_call_api($method, $url, $options, $debug_info);
    }
}
