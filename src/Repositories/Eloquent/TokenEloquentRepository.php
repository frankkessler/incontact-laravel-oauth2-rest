<?php

namespace Frankkessler\Incontact\Repositories\Eloquent;

use DateInterval;
use Datetime;
use Frankkessler\Guzzle\Oauth2\AccessToken;
use Frankkessler\Incontact\IncontactConfig;
use Frankkessler\Incontact\Models\IncontactToken;
use Frankkessler\Incontact\Repositories\TokenRepositoryInterface;

class TokenEloquentRepository implements TokenRepositoryInterface
{
    public function __construct($config = [])
    {
    }

    public function setAccessToken($access_token, $user_id = null)
    {
        $record = $this->getTokenRecord($user_id);

        $record->access_token = $access_token;
        $record->save();
    }

    public function setRefreshToken($refresh_token, $user_id = null)
    {
        $record = $this->getTokenRecord($user_id);

        $record->refresh_token = $refresh_token;
        $record->save();
    }

    public function getTokenRecord($user_id = null)
    {
        if (is_null($user_id)) {
            $user_id = IncontactConfig::get('incontact.storage_global_user_id');
            if (is_null($user_id)) {
                if (class_exists('\Auth') && $user = \Auth::user()) {
                    $user_id = $user->id;
                } else {
                    $user_id = 0;
                }
            }
        }

        $record = IncontactToken::findByUserId($user_id)->first();

        if (!$record) {
            $record = new IncontactToken();
            $record->user_id = $user_id;
        }

        if ($record->expires) {
            $expires = date_create_from_format('Y-m-d H:i:s', $record->expires);
            if ($expires instanceof DateTime) {
                $record->expires = $expires->format('U');
            }
        }

        return $record;
    }

    public function setTokenRecord(AccessToken $token, $user_id = null)
    {
        if (is_null($user_id)) {
            $user_id = IncontactConfig::get('incontact.storage_global_user_id');
            if (is_null($user_id)) {
                if (class_exists('\Auth') && $user = \Auth::user()) {
                    $user_id = $user->id;
                } else {
                    $user_id = 0;
                }
            }
        }

        $record = IncontactToken::findByUserId($user_id)->first();

        if (!$record) {
            $record = new IncontactToken();
            $record->user_id = $user_id;
        }

        $token_data = $token->getData();

        $record->access_token = $token->getToken();
        $record->refresh_token = $token->getRefreshToken()->getToken();
        $record->instance_base_url = $token_data['resource_server_base_uri'];
        $record->refresh_instance_url = $token_data['refresh_token_server_uri'];
        $record->scope = $token_data['scope'];
        $record->agent_id = $token_data['agent_id'];
        $record->team_id = $token_data['team_id'];
        $record->business_unit = $token_data['bus_no'];

        $expires_in = (isset($token_data['expires_in']) && $token_data['expires_in'] > 0) ? $token_data['expires_in'] : 3600;

        //give 5 second buffer
        $expires_in = $expires_in - 5;

        $date = new DateTime();
        $interval = new DateInterval('PT'.$expires_in.'S');
        $date->add($interval);

        $record->expires = $date->format('Y-m-d H:i:s');

        $record->save();

        return $record;
    }
}
