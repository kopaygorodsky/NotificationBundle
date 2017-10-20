<?php

namespace Kopay\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DisableDefaultProviders implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {

        if (empty($config = $container->getExtensionConfig('kopay_notification'))) {
            return;
        }

        $config = $config[0];

        if (isset($config['types'])) {
            if (isset($config['types']['email']['default_provider']) && false === $config['types']['email']['default_provider']) {
                $container->removeDefinition('kopaygorodsky_notification.sending_provider.email');
            }
            if (isset($config['types']['push']['default_provider']) && false === $config['types']['push']['default_provider']) {
                $container->removeDefinition('kopaygorodsky_notification.sending_provider.push');
            }
        }

        $taggedServices = $container->findTaggedServiceIds('kopaygorodsky_notifications.sending_provider');
        $providers = ['email' => 0, 'push' => 0];

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                foreach ($attributes as $name => $value) {
                    if ($name === 'type') {
                        $providers[$value]++;
                    }
                }
            }
        }

        foreach ($providers as $type => $count) {
            if ($count === 0) {
                throw new \LogicException(sprintf('You must define at least one provider for \'%s\' notification type or enable default one', $type));
            }
        }
    }
}