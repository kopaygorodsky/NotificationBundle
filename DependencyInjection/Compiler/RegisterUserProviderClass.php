<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\DependencyInjection\Compiler;

use Kopay\NotificationBundle\DependencyInjection\KopayNotificationExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterUserProviderClass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $bundleConfig = $container->getExtensionConfig('kopay_notification');

        $isRecipientClassDefined = isset($bundleConfig[0]['recipientClass']) && false !== $bundleConfig[0]['recipientClass'];

        //do not check security provider if recipientClass is defined
        if ($isRecipientClassDefined) {
            $container->removeDefinition(KopayNotificationExtension::METADATA_LISTENER);
        }

        $securityConfig = $container->getExtensionConfig('security');

        if (isset($securityConfig[0]['providers']) && !empty($providers = $securityConfig[0]['providers'])) {
            reset($providers);
            $defaultProvider     = $providers[key($providers)];
            $defaultProviderType = key($defaultProvider);
            
            if ('entity' !== $defaultProviderType && !$isRecipientClassDefined) {
                throw new \LogicException(sprintf('Default provider is not entity type, please define \'recipientClass\' parameter'));
            }

            $isAuthEnabled = isset($bundleConfig[0]['types']['push']['server']['auth']) && false !== $bundleConfig[0]['types']['push']['server']['auth'];
            $userProvider  = null;

            switch ($defaultProviderType) {
                case 'entity':
                    if (!$isRecipientClassDefined) {
                        $userClass        = $defaultProvider[$defaultProviderType]['class'];
                        $metadataListener = $container->getDefinition(KopayNotificationExtension::METADATA_LISTENER);
                        $metadataListener->addArgument($userClass);
                    }
                    $userProvider = new Reference('security.user.provider.concrete.user');

                    break;
                case 'chain':
                    $userProvider = new Reference('security.user.provider.chain');

                    break;
                case 'id':
                    $userProvider = new Reference($defaultProvider['id']);

                    break;
            }

            if ($userProvider && $isAuthEnabled) {
                $container->getDefinition('kopay_notification.websockets.auth_provider')
                    ->setArgument(0, $userProvider);
            }
        }
    }
}
