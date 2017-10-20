<?php

namespace Kopay\NotificationBundle\Entity;


interface NotificationEmailInterface extends NotificationMessageInterface
{
    public function getFromEmail(): string;
}