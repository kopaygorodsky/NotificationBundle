<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Kopay\NotificationBundle\Entity\NotificationPush;
use Ratchet\Client;

class PushNotificationProvider extends AbstractNotificationProvider
{
    /**
     * @var int
     */
    protected $port;

    public function __construct(int $port)
    {
        $this->port = $port;
    }

    public function send(NotificationMessageInterface $notification): void
    {
        Client\connect('ws://localhost:'.$this->port)->then(function($conn) use ($notification) {

            $receivers = $this->getReceiversIdentity($notification);

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

    /**
     * Get list of ids
     *
     * @param NotificationMessageInterface $notification
     * @return array
     */
    public function getReceiversIdentity(NotificationMessageInterface $notification): array
    {
        return $notification->getRecipientsItems()->map(function ($recipientItem) {
            return $recipientItem->getRecipient()->getId()();
        })->toArray();
    }
}