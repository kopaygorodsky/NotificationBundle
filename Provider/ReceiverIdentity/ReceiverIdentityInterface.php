<?php

namespace Kopay\NotificationBundle\Provider\ReceiverIdentity;

interface ReceiverIdentityInterface
{
    public function getIdentities(array $receivers): array;

    public function getIdentity($receiver);
}