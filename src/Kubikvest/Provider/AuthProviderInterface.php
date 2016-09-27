<?php

namespace Kubikvest\Provider;

use GuzzleHttp\ClientInterface;

interface AuthProviderInterface
{
    public function __construct(ClientInterface $client);
    public function login();
}
