<?php

namespace Kubikvest\Provider;

use GuzzleHttp\ClientInterface;

class VkProvider implements AuthProviderInterface
{
    protected $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }
    public function login()
    {
        $response = $this->httpClient->request('GET', '/access_token', [
            'query' => [
                'client_id'     => $app['client_id'],
                'client_secret' => $app['client_secret'],
                'redirect_uri'  => $app['redirect_uri'],
                'code'          => $code,
            ]
        ]);
    }
}
