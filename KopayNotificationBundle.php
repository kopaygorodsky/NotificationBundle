<?php

namespace Kopay\NotificationBundle;

use Kopay\NotificationBundle\DependencyInjection\Compiler\RegisterTagServicesPass;
use Kopay\NotificationBundle\DependencyInjection\Compiler\RegisterUserProviderClass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
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
        // set 1 priority to run it before doctrine compiler passes
        $container->addCompilerPass(new RegisterUserProviderClass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
