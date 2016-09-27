<?php

namespace Kubikvest\Provider;

class AuthProvider
{
    const PROVIDER_VK = 'vk';
    const PROVIDER_FB = 'fb';
    protected $providers = [
        self::PROVIDER_FB => '\\Kubikvest\\Provider\\FbProvider',
        self::PROVIDER_VK => '\\Kubikvest\\Provider\\VkProvider',
    ];

    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @param string $id
     *
     * @return AuthProviderInterface
     */
    public function getProvider($id)
    {
        if (!array_key_exists($id, $this->providers)) {
            throw new \RuntimeException;
        }
        $provider = $this->providers[$id];

        return new $provider();
    }
}
