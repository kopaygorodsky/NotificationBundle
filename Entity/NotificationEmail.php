<?php

namespace Kopay\NotificationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class NotificationEmail extends Notification implements NotificationEmailInterface
{
    /**
     * @var string
     * @Assert\Email(strict=true)
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