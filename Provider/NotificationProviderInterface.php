<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationProviderInterface
{
    public function send(NotificationMessageInterface $message): void;

    public function supports(NotificationMessageInterface $message): bool;
}
