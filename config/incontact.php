<?php

return [
    'oauth' => [
        'auth_method' => env('INCONTACT_OAUTH_AUTH_METHOD', 'password'),

        'domain' => env('INCONTACT_OAUTH_DOMAIN', 'api.incontact.com'),

        'authorize_uri' => env('INCONTACT_OAUTH_AUTHORIZE_URI', '/InContactAuthorizationServer/Authenticate'),

        'token_uri' => env('INCONTACT_OAUTH_TOKEN_URI', '/InContactAuthorizationServer/Token'),

        'callback_url' => env('INCONTACT_OAUTH_CALLBACK_URL', ''),

        'consumer_token' => env('INCONTACT_OAUTH_CONSUMER_TOKEN', null),

        'consumer_secret' => env('INCONTACT_OAUTH_CONSUMER_SECRET', null),

        'username' => env('INCONTACT_OAUTH_USERNAME', null),

        'password' => env('INCONTACT_OAUTH_PASSWORD', null),

        'scopes' => env('INCONTACT_OAUTH_SCOPES', 'RealTimeApi AdminApi AuthenticationApi PatronApi AgentApi CustomApi ReportingApi'),
    ],
    'api_version'            => env('INCONTACT_API_VERSION', 'v6.0'),
    'storage_type'           => 'eloquent',
    'storage_global_user_id' => null,
    'enable_oauth_routes'    => env('INCONTACT_ENABLE_OAUTH_ROUTES', false),
    'logger'                 => env('INCONTACT_LOGGER_CLASS', null),
];
