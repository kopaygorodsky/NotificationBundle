<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Provider\ReceiverIdentity;

interface ReceiverIdentityInterface
{
    public function getIdentities(array $receivers): array;

    public function getIdentity($receiver);
}
