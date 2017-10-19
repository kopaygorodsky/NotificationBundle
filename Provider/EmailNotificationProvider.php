<?php

namespace Kopaygorodsky\NotificationBundle\Provider;

use Kopaygorodsky\NotificationBundle\Entity\NotificationEmailInterface;
use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;
use Symfony\Component\Templating\EngineInterface;

class EmailNotificationProvider extends AbstractEmailNotificationProvider
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
        $recipients = $notification->getRecipients();

        $message = (new \Swift_Message($notification->getTitle()))
            ->setFrom($notification->getFromEmail())
            ->setTo($this->getReceiversEmails($notification))
            ->setBody(
                $notification->getMessage(),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }

    /**
     * @param NotificationEmailInterface $notification
     * @return array
     */
    protected function getReceiversEmails(NotificationEmailInterface $notification): array
    {
        $recipients = $notification->getRecipients();
        $emails = [];

//        foreach ($recipients as $recipient) {
//            $emails[] = $recipient->getEmail();
//        }

        return $emails;
    }
}