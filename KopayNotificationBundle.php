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
                'kopay_notify.console.send_notification',
                'kopay_notify.sending_provider'
            )
        );
        // set 1 priority to run it before doctrine compiler passes
        $container->addCompilerPass(new RegisterUserProviderClass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
