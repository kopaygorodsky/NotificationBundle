<?php

namespace Kopay\NotificationBundle\DependencyInjection;

use JMS\JobQueueBundle\JMSJobQueueBundle;
use Kopay\NotificationBundle\Console\NotificationCommandInterface;
use Kopay\NotificationBundle\Job\JmsJobBundleProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
final class KopayNotificationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        //dump($config);die;

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->validateSendingProviders($config, $container);
        $this->validateJobProvider($config, $container);
        $this->validateConsoleCommand($config, $container);

    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    private function validateSendingProviders(array $config, ContainerBuilder $container): void
    {
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

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    private function validateJobProvider(array $config, ContainerBuilder $container): void
    {
        $registry = $container->findDefinition('kopaygorodsky_notification.job_provider');

        if ($registry->getClass() === JmsJobBundleProvider::class) {
            $bundles = array_flip($container->getParameter('kernel.bundles'));

            if (false === array_key_exists(JMSJobQueueBundle::class, $bundles)) {
                throw new \LogicException(
                    sprintf(
                        'Cannot register "%s" without "%s registered".',
                        JmsJobBundleProvider::class,
                        JMSJobQueueBundle::class
                    )
                );
            }
        }
    }

    /**
     * @param $config
     * @param ContainerBuilder $container
     */
    private function validateConsoleCommand(array $config, ContainerBuilder $container): void
    {
        $registry = $container->findDefinition('kopaygorodsky_notification.console.send_notification');

        if (!array_key_exists(NotificationCommandInterface::class, class_implements($registry->getClass()))) {
            throw new \LogicException(sprintf('Console command %s must implement %s', $registry->getClass(), NotificationCommandInterface::class));
        }
    }
}
