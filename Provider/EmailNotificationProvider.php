<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationEmailInterface;
use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Kopay\NotificationBundle\Provider\ReceiverIdentity\ReceiverIdentityInterface;

class EmailNotificationProvider implements NotificationProviderInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var ReceiverIdentityInterface
     */
    protected $identity;

    public function __construct(\Swift_Mailer $mailer, ReceiverIdentityInterface $identity)
    {
        $this->mailer   = $mailer;
        $this->identity = $identity;
    }

    public function send(NotificationMessageInterface $notification): void
    {
        $receivers = $this->identity->getIdentities(
            array_map(
                function ($recipientItem) {
                    return $recipientItem->getRecipient();
                },
                $notification->getRecipientsItems()->toArray()
            )
        );

        $message = (new \Swift_Message($notification->getTitle()))
            ->setFrom($notification->getFromEmail())
            ->setTo($receivers)
            ->setBody(
                $notification->getMessage(),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    public function supports(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationEmailInterface;
    }
}
