<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Capsule\Manager as Capsule;

include __DIR__.'/../migrations/incontact.php';

class DbTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function setUp()
    {
        parent::setUp();

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

        $tokenMigration = new CreateIncontactTokensTable();
        $tokenMigration->up();
    }

    public function testEloquentSetRepository()
    {
        $user_id = 1;

        $accessTokenString = 'TEST_TOKEN';
        $refreshTokenString = 'TEST_REFRESH_TOKEN';
        $expires = 1473913598;

        $data = array_replace(json_decode($this->returnAuthorizationCodeAccessTokenResponse(), true), [
            'refresh_token' => $refreshTokenString,
            'expires'       => $expires,
        ]);

        $accessToken = new CommerceGuys\Guzzle\Oauth2\AccessToken($accessTokenString, 'bearer', $data);

        $repository = new \Frankkessler\Incontact\Repositories\Eloquent\TokenEloquentRepository();
        $repository->setTokenRecord($accessToken, $user_id);

        $tokenRecord = $repository->getTokenRecord($user_id);

        $this->assertEquals($accessTokenString, $tokenRecord->access_token);
        $this->assertEquals($refreshTokenString, $tokenRecord->refresh_token);
        $this->assertEquals($data['resource_server_base_uri'], $tokenRecord->instance_base_url);
        $this->assertEquals($user_id, $tokenRecord->user_id);


        $newAccessTokenString = 'TEST_TOKEN_NEW';
        $newRefreshTokenString = 'TEST_REFRESH_TOKEN_NEW';
        $repository->setAccessToken($newAccessTokenString, $user_id);
        $repository->setRefreshToken($newRefreshTokenString, $user_id);

        $tokenRecord = $repository->getTokenRecord($user_id);

        $this->assertEquals($newAccessTokenString, $tokenRecord->access_token);
        $this->assertEquals($newRefreshTokenString, $tokenRecord->refresh_token);
    }

    public function testAuthorizationCodeFlow()
    {
        $code = 'AUTHORIZATION_CODE';

        \Frankkessler\Incontact\IncontactConfig::set('incontact.oauth.consumer_token', 'TEST_CLIENT_ID');
        \Frankkessler\Incontact\IncontactConfig::set('incontact.oauth.consumer_secret', 'TEST_CLIENT_SECRET');

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->returnAuthorizationCodeAccessTokenResponse()),
        ]);

        $handler = HandlerStack::create($mock);

        $auth = new \Frankkessler\Incontact\Authentication();

        $request = new \Illuminate\Http\Request(['resource_server_base_uri' => 'https://api.incontact.com']);
        $options = ['handler' => $handler];
        $result = $auth->processAuthenticationCode($code, $request, $options);

        $this->assertEquals('Token record set successfully', $result);

        $repository = new \Frankkessler\Incontact\Repositories\Eloquent\TokenEloquentRepository();

        $tokenRecord = $repository->getTokenRecord();

        $this->assertEquals('AUTH_TEST_TOKEN', $tokenRecord->access_token);
        $this->assertEquals('AUTH_TEST_REFRESH_TOKEN', $tokenRecord->refresh_token);
    }

    public function returnAuthorizationCodeAccessTokenResponse()
    {
        return
        '{
            "access_token": "AUTH_TEST_TOKEN",
            "refresh_token": "AUTH_TEST_REFRESH_TOKEN",
            "resource_server_base_uri": "https://api.incontact.com",
            "refresh_token_server_uri": "https://api.incontact.com",
            "expires": 1473913598,
            "token_type": "bearer",
            "scope": "AdminApi ReportingApi RealTimeApi",
            "agent_id": "999999",
            "team_id": "999999",
            "bus_no": "999999"
        }';
    }
}
