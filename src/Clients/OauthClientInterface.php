<?php

namespace Frankkessler\Incontact\Clients;

interface OauthClientInterface
{
    public function returnClientGrantTypeClass();

    public function call_api($method, $url, $options=[], $debug_info=[]);
}