<?php

namespace Kopay\NotificationBundle;

use Kopay\NotificationBundle\DependencyInjection\Compiler\RegisterTagServicesPass;
use Kopay\NotificationBundle\DependencyInjection\Compiler\ValidateConsoleCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KopayNotificationBundle extends Bundle
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
        $container->addCompilerPass(
            new ValidateConsoleCommand('kopaygorodsky_notification.console.send_notification')
        );
    }
}
