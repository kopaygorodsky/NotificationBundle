<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class NotificationEmail extends Notification implements NotificationEmailInterface
{
    /**
     * @var string
     * @Assert\Email(strict=true)
     */
    protected $fromEmail;

    public function __construct(string $title, string $message, string $fromEmail, bool $visible, array $recipients)
    {
        parent::__construct($title, $message, $visible, $recipients);
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }
}
