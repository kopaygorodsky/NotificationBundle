<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Server\Security;

use function GuzzleHttp\Psr7\parse_query;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Provider\JWTProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Ratchet\ConnectionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class JwtAuthProvider implements AuthenticatorInterface
{
    protected $authenticator;

    public function __construct(JWTProvider $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function authenticate(ConnectionInterface $connection): ? TokenInterface
    {
        $params = parse_query($connection->httpRequest->getUri()->getQuery());

        if (!isset($params['token'])) {
            return null;
        }

        return $this->authenticator->authenticate(new PreAuthenticationJWTUserToken($params['token']));
    }
}
