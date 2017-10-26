<?php

namespace Kopay\NotificationBundle\Server\Security;

use function GuzzleHttp\Psr7\parse_query;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Provider\JWTProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Ratchet\ConnectionInterface;
use function Symfony\Component\Debug\Tests\testHeader;

class JwtAuthProvider implements AuthProviderInterface
{
    protected $authenticator;

    public function __construct(JWTProvider $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function authenticate(ConnectionInterface $connection): ? JWTUserToken
    {
        $params = parse_query($connection->httpRequest->getUri()->getQuery());

        if (!isset($params['token'])) {
            return null;
        }

        return $this->authenticator->authenticate(new PreAuthenticationJWTUserToken($params['token']));
    }
}