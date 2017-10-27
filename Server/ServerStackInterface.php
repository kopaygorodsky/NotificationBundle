<?php

namespace Kopay\NotificationBundle\Server;

interface ServerStackInterface
{
    public function run(): void;

    public function getPort(): int;

    public function getHost(): string;
}