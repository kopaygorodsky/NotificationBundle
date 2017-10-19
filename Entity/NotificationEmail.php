<?php

namespace Kopaygorodsky\NotificationBundle\Entity;

class NotificationEmail extends Notification implements NotificationEmailInterface
{
    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @param string $from
     */
    public function setFromEmail(string $from): void
    {
        $this->fromEmail = $from;
    }
}