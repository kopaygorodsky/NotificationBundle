<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationEmailInterface;
use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

class EmailNotificationProvider extends AbstractNotificationProvider
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    public function send(NotificationMessageInterface $notification): void
    {
        $message = (new \Swift_Message($notification->getTitle()))
            ->setFrom($notification->getFromEmail())
            ->setTo($this->getReceiversIdentity($notification))
            ->setBody(
                $notification->getMessage(),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    /**
     * Return array of emails
     *
     * @param NotificationMessageInterface $notification
     * @return array
     */
    protected function getReceiversIdentity(NotificationMessageInterface $notification): array
    {
        return $notification->getRecipientsItems()->map(function ($recipientItem) {
            return $recipientItem->getRecipient()->getEmail()();
        })->toArray();
    }

    public function supports(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationEmailInterface;
    }
}