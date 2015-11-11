<?php

return [
    'oauth' => [
        'domain' => env('INCONTACT_OAUTH_DOMAIN','api.incontact.com'),

        'authorize_uri' => env('INCONTACT_OAUTH_AUTHORIZE_URI','/InContactAuthorizationServer/Authenticate'),

        'token_uri' => env('INCONTACT_OAUTH_TOKEN_URI','/InContactAuthorizationServer/Token'),

        'callback_url' => env('INCONTACT_OAUTH_CALLBACK_URL',''),

        'consumer_token' => env('INCONTACT_OAUTH_CONSUMER_TOKEN',null),

        'consumer_secret' => env('INCONTACT_OAUTH_CONSUMER_SECRET',null),

        'scopes' => env('INCONTACT_OAUTH_SCOPES','RealTimeApi AdminApi AuthenticationApi PatronApi AgentApi CustomApi ReportingApi'),
    ],
    'storage_type' => 'eloquent',
    'storage_global_user_id' => null,
    'enable_oauth_routes' =>  env('INCONTACT_ENABLE_OAUTH_ROUTES',false),
];