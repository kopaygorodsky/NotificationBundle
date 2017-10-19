<?php

namespace Kopaygorodsky\NotificationBundle;

use Kopaygorodsky\NotificationBundle\DependencyInjection\Compiler\RegisterTagServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KopaygorodskyNotificationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new RegisterTagServicesPass(
                'kopaygorodsky_notification.console.send_notification',
                'kopaygorodsky_notifications.send_provider'
            )
        );
    }
}
