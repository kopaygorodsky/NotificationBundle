<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Kopay\NotificationBundle\Entity\NotificationPush;
use Kopay\NotificationBundle\Provider\ReceiverIdentity\ReceiverIdentityInterface;
use Ratchet\Client;

class PushNotificationProvider implements NotificationProviderInterface
{
    /**
     * @var int
     */
    protected $port;

    /**
     * @var ReceiverIdentityInterface
     */
    protected $identity;

    public function __construct(ReceiverIdentityInterface $identity, int $port)
    {
        $this->port = $port;
        $this->identity = $identity;
    }

    public function send(NotificationMessageInterface $notification): void
    {
        Client\connect('ws://localhost:'.$this->port)->then(function($conn) use ($notification) {

            $receivers = $this->identity->getIdentities(
                array_map(
                    function ($recipientItem) {
                        return $recipientItem->getRecipient();
                    },
                    (array)$notification->getRecipientsItems()
                )
            );

            foreach ($receivers as $receiver) {
                $data = [
                    'data' => [
                        'message' => $notification->getMessage(),
                        'value' => $notification->getValue(),
                        'title' => $notification->getTitle()
                    ],
                    'recipient' => $receiver
                ];
                $conn->send($data);
            }

            $conn->close();

        }, function ($e) {
            throw $e;
        });

    }

    public function supports(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationPush;
    }
}