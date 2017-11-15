<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @var string
     */
    protected $host;

    /**
     * @var ReceiverIdentityInterface
     */
    protected $identity;

    public function __construct(ReceiverIdentityInterface $identity, string $host, int $port)
    {
        $this->host     = $host;
        $this->port     = $port;
        $this->identity = $identity;
    }

    public function send(NotificationMessageInterface $notification): void
    {
        Client\connect(sprintf('ws://%s:%d', $this->host, $this->port))->then(function ($conn) use ($notification) {
            $receivers = $this->identity->getIdentities(
                array_map(
                    function ($recipientItem) {
                        return $recipientItem->getRecipient();
                    },
                    (array) $notification->getRecipientsItems()
                )
            );

            foreach ($receivers as $receiver) {
                $data = [
                    'data' => [
                        'message' => $notification->getMessage(),
                        'value'   => $notification->getValue(),
                        'title'   => $notification->getTitle(),
                    ],
                    'recipient' => $receiver,
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
