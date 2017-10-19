<?php

namespace Kopaygorodsky\NotificationBundle\Controller;

use Kopaygorodsky\NotificationBundle\Entity\NotificationEmail;
use Kopaygorodsky\NotificationBundle\Entity\NotificationPush;
use Kopaygorodsky\NotificationBundle\Event\NotificationEvent;
use Kopaygorodsky\NotificationBundle\Event\NotificationEventInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $email = new NotificationEmail();
        $email->setTitle('Pidor');
        $email->setMessage('Tu');
        $email->setFromEmail('lol@lol.com');
        $this->get('event_dispatcher')->dispatch(NotificationEventInterface::NOTIFICATION_CREATED, new NotificationEvent($email));
        die('controller');
    }
}
