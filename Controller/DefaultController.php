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
        die('controller');
    }
}
