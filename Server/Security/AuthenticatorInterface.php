<?php

namespace Kopay\NotificationBundle\Server\Security;

use Ratchet\ConnectionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

interface AuthenticatorInterface
{
    public function authenticate(ConnectionInterface $connection): ? TokenInterface;
}