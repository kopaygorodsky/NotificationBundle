<?php

namespace Kopay\NotificationBundle\Server\Security;

use Ratchet\ConnectionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface AuthProviderInterface
{
    public function authenticate(ConnectionInterface $connection): TokenInterface;
}