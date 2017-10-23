<?php

namespace Kopay\NotificationBundle\DependencyInjection\Compiler;

use Kopay\NotificationBundle\DependencyInjection\KopayNotificationExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterUserProviderClass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $bundleConfig = $container->getExtensionConfig('kopay_notify');

        //do not check security provider if recipientClass is defined
        if (isset($bundleConfig[0]['recipientClass'])) {
            if (false === $bundleConfig[0]['recipientClass']) {
                $container->removeDefinition(KopayNotificationExtension::METADATA_LISTENER);
            }
            return;
        }

        $securityConfig = $container->getExtensionConfig('security');

        if (isset($securityConfig[0]['providers']) && !empty($providers = $securityConfig[0]['providers'])) {
            reset($providers);
            $defaultProvider = $providers[key($providers)];
            $defaultProviderType = key($defaultProvider);

            if ('entity' === $defaultProviderType) {
                $userClass = $defaultProvider[$defaultProviderType]['class'];
                $metadataListener = $container->getDefinition(KopayNotificationExtension::METADATA_LISTENER);
                $metadataListener->addArgument($userClass);
                return;
            }
        }
        $container->removeDefinition(KopayNotificationExtension::METADATA_LISTENER);
    }
}