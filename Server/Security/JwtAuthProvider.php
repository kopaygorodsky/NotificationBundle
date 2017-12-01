<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Server\Security;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
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

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(UserProviderInterface $userProvider, JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher, EntityManager $entityManager)
    {
        $this->userProvider          = $userProvider;
        $this->jwtManager            = $jwtManager;
        $this->dispatcher            = $dispatcher;
        $this->entityManager = $entityManager;
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
                throw new ExpiredTokenException('Token is expired');
            }

            throw new InvalidTokenException('Invalid JWT Token', 0, $e);
        }

        try {
            $user = $this->userProvider->loadUserByUsername($payload['username']);
        } catch (DBALException $exception) {
            if (!$this->canReconnect($exception) || $this->entityManager->getConnection()->ping()) {
                throw $exception;
            }
            //if users are stored in db and provider lost connection to db we will try to reconnect and load user again
            $this->reconnectDb();
            $user = $this->userProvider->loadUserByUsername($payload['username']);
        }

        $authToken = new JWTUserToken($user->getRoles());
        $authToken->setUser($user);
        $authToken->setRawToken($preAuthToken->getCredentials());

        $event = new JWTAuthenticatedEvent($payload, $authToken);
        $this->dispatcher->dispatch(Events::JWT_AUTHENTICATED, $event);

        return $authToken;
    }

    private function reconnectDb(): void
    {
        $tries = 5;
        $connection = $this->entityManager->getConnection();
        $failedException = new \PDOException('Can not reconnect');

        while ($tries > 0) {
            try {
                $connection->close();
                $connection->connect();

                if (!$connection->ping()) {
                    throw $failedException;
                }

                return;
            } catch (\Exception $exception) {
                --$tries;
            }
        }

        throw $failedException;
    }

    private function canReconnect(\Exception $e): bool
    {
        return false !== stripos($e->getMessage(), 'MySQL server has gone away')
            || false !== stripos($e->getMessage(), 'Error while sending QUERY packet');
    }
}
