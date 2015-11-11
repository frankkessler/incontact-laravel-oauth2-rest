<?php

namespace Frankkessler\Incontact\Repositories\Eloquent;

use Frankkessler\Incontact\Repositories\TokenRepositoryInterface;
use Frankkessler\Incontact\Models\IncontactToken;
use CommerceGuys\Guzzle\Oauth2\AccessToken;
use Auth;
use Config;

class TokenEloquentRepository implements TokenRepositoryInterface{

    public function  __construct($config=[]){

    }

    public function setAccessToken($access_token, $user_id=null){
        $record = $this->getTokenRecord($user_id);

        $record->access_token = $access_token;
        $record->save();
    }

    public function setRefreshToken($refresh_token, $user_id=null){
        $record = $this->getTokenRecord($user_id);

        $record->refresh_token = $refresh_token;
        $record->save();
    }

    public function getTokenRecord($user_id=null){
        if(is_null($user_id)){
            $user_id = Config::get('incontact.storage_global_user_id');
            if(is_null($user_id)){
                $user = Auth::user();
                if($user){
                    $user_id = $user->id;
                }else{
                    $user_id = 0;
                }
            }
        }

        $record = IncontactToken::findByUserId($user_id)->first();

        if(!$record) {
            $record = new IncontactToken;
            $record->user_id = $user_id;
        }
        return $record;
    }

    public function setTokenRecord(AccessToken $token, $user_id=null){
        if(is_null($user_id)){
            $user_id = Config::get('incontact.storage_global_user_id');
            if(is_null($user_id)){
                $user = Auth::user();
                if($user){
                    $user_id = $user->id;
                }else{
                    $user_id = 0;
                }
            }
        }

        $record = IncontactToken::findByUserId($user_id)->first();

        if(!$record) {
            $record = new IncontactToken;
            $record->user_id = $user_id;
        }

        $token_data = $token->getData();

        $record->access_token = $token->getToken();
        $record->refresh_token = $token->getRefreshToken()->getToken();
        $record->instance_base_url = $token_data['resource_server_base_uri'];

        $record->save();

        return $record;
    }
}