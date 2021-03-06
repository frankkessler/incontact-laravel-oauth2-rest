<?php


class ClientTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function testImplicitClient()
    {
        $client = new \Frankkessler\Incontact\Clients\Implicit();

        $refreshGrant = $client->returnClientRefreshGrantTypeClass('');

        $this->assertTrue($refreshGrant instanceof \Frankkessler\Guzzle\Oauth2\GrantType\RefreshTokenGrantTypeInterface);
    }
}
