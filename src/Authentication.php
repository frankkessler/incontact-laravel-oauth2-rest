<?php

namespace Frankkessler\Incontact;

use GuzzleHttp\Client;
use CommerceGuys\Guzzle\Oauth2\Oauth2Client;
use CommerceGuys\Guzzle\Oauth2\GrantType\AuthorizationCode;
use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use CommerceGuys\Guzzle\Oauth2\Utilities;
use Frankkessler\Incontact\Repositories\TokenRepository;
use Frankkessler\Incontact\Incontact;
use Frankkessler\Incontact\IncontactConfig;

class Authentication{

    public static function returnAuthorizationLink(){
        $service_authorization_url = 'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.authorize_uri');

        $oauth_config = [
            'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
            'redirect_uri' => IncontactConfig::get('incontact.oauth.callback_url'),
            'scope'=> IncontactConfig::get('incontact.oauth.scopes'),
        ];

        return '<a href="'.Utilities::getAuthorizationUrl($service_authorization_url, $oauth_config).'&state=myState">Login to Incontact</a>';
    }

    public static function processAuthenticationCode($code, $request){
        $repository = new TokenRepository;

        $base_uri = $request->input('resource_server_base_uri');

        $oauth2Client = new Oauth2Client([
            'base_uri' => $base_uri,
        ]);

        $authorization_config = [
            'code' => $code,
            'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
            'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            'redirect_uri' => IncontactConfig::get('incontact.oauth.callback_url'),
            'token_url' =>'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.token_uri'),
            'auth_location' => 'body',
        ];
        $oauth2Client->setGrantType(new AuthorizationCode($authorization_config));

        $refresh_token = '';
        if($refresh_token) {
            $refresh_config = [
                'refresh_token' => $refresh_token,
                'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
                'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            ];
            $oauth2Client->setRefreshTokenGrantType(new RefreshToken($refresh_config));
        }

        $access_token = $oauth2Client->getAccessToken();

        $repository->store->setTokenRecord($access_token);

        return 'Token record set successfully';
    }

    public static function processAuthenticationPassword($request){
        $repository = new TokenRepository;

        $base_uri = $request->input('resource_server_base_uri');

        $oauth2Client = new Oauth2Client([
            'base_uri' => $base_uri,
        ]);

        $authorization_config = [
            'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
            'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            'redirect_uri' => IncontactConfig::get('incontact.oauth.callback_url'),
            'token_url' =>'https://'.IncontactConfig::get('incontact.oauth.domain').IncontactConfig::get('incontact.oauth.token_uri'),
            'auth_location' => 'body',
        ];
        $oauth2Client->setGrantType(new AuthorizationCode($authorization_config));

        $refresh_token = '';
        if($refresh_token) {
            $refresh_config = [
                'refresh_token' => $refresh_token,
                'client_id' => IncontactConfig::get('incontact.oauth.consumer_token'),
                'client_secret' => IncontactConfig::get('incontact.oauth.consumer_secret'),
            ];
            $oauth2Client->setRefreshTokenGrantType(new RefreshToken($refresh_config));
        }

        $access_token = $oauth2Client->getAccessToken();

        $repository->store->setTokenRecord($access_token);

        return 'Token record set successfully';
    }



}