<?php

namespace Kopaygorodsky\NotificationBundle\Entity;


interface NotificationEmailInterface extends NotificationMessageInterface
{
    public function getFromEmail(): string;
}