<?php

namespace Kopay\NotificationBundle;

use Kopay\NotificationBundle\DependencyInjection\Compiler\DisableDefaultProviders;
use Kopay\NotificationBundle\DependencyInjection\Compiler\RegisterTagServicesPass;
use Kopay\NotificationBundle\DependencyInjection\Compiler\ValidateConsoleCommand;
use Kopay\NotificationBundle\DependencyInjection\Compiler\ValidateJobProvider;
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
                'kopaygorodsky_notifications.sending_provider'
            )
        );
    }
}
