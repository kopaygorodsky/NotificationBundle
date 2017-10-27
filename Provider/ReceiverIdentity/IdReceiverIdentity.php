<?php

namespace Kopay\NotificationBundle\Provider\ReceiverIdentity;

class IdReceiverIdentity implements ReceiverIdentityInterface
{
    public function getIdentity($receiver)
    {
        return $receiver->getId();
    }

    public function getIdentities(array $receivers): array
    {
        return array_map(function($receiver){
            return $this->getIdentity($receiver);
        }, $receivers);
    }
}