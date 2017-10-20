<?php

namespace Kopay\NotificationBundle\Controller;

use Kopay\NotificationBundle\Entity\NotificationEmail;
use Kopay\NotificationBundle\Event\NotificationEvent;
use Kopay\NotificationBundle\Event\NotificationEventInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $email = new NotificationEmail();
        $email->setTitle('test');
        $email->setMessage('test');
        $email->setFromEmail('lol@lol.com');
        $this->get('event_dispatcher')->dispatch(NotificationEventInterface::NOTIFICATION_CREATED, new NotificationEvent($email));
        die('controller');
    }
}
