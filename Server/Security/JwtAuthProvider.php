<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Server\Security;

use function GuzzleHttp\Psr7\parse_query;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JwtAuthProvider implements AuthenticatorInterface
{
    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     * @var JWTTokenManagerInterface
     */
    protected $jwtManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function __construct(UserProviderInterface $userProvider, JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher)
    {
        $this->userProvider          = $userProvider;
        $this->jwtManager            = $jwtManager;
        $this->dispatcher            = $dispatcher;
    }

    public function authenticate(ConnectionInterface $connection): ? TokenInterface
    {
        $params = parse_query($connection->httpRequest->getUri()->getQuery());

        if (!isset($params['token'])) {
            return null;
        }

        $preAuthToken = new PreAuthenticationJWTUserToken($params['token']);

        try {
            if (!$payload = $this->jwtManager->decode($preAuthToken)) {
                throw new InvalidTokenException('Invalid JWT Token');
            }

            $preAuthToken->setPayload($payload);
        } catch (JWTDecodeFailureException $e) {
            if (JWTDecodeFailureException::EXPIRED_TOKEN === $e->getReason()) {
                throw new ExpiredTokenException();
            }

            throw new InvalidTokenException('Invalid JWT Token', 0, $e);
        }

        $user = $this->userProvider->loadUserByUsername($payload['username']);

        $authToken = new JWTUserToken($user->getRoles());
        $authToken->setUser($user);
        $authToken->setRawToken($preAuthToken->getCredentials());

        $event = new JWTAuthenticatedEvent($payload, $authToken);
        $this->dispatcher->dispatch(Events::JWT_AUTHENTICATED, $event);

        return $authToken;
    }
}
