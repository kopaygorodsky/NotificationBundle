<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Server;

interface ServerStackInterface
{
    public function run(): void;

    public function getPort(): int;

    public function getHost(): string;
}
