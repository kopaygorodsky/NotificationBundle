<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
                'kopay_notification.console.send_notification',
                'kopay_notification.sending_provider'
            )
        );
        // set 1 priority to run it before doctrine compiler passes
        $container->addCompilerPass(new RegisterUserProviderClass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
    }
}
