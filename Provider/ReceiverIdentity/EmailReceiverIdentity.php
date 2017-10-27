<?php

namespace Kopay\NotificationBundle\Provider\ReceiverIdentity;

class EmailReceiverIdentity implements ReceiverIdentityInterface
{
    public function getIdentity($receiver): string
    {
        return $receiver->getEmail();
    }

    public function getIdentities(array $receivers): array
    {
        return array_map(function($receiver){
            return $this->getIdentity($receiver);
        }, $receivers);
    }
}