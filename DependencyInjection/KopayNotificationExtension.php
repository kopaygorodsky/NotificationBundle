<?php

namespace Kopay\NotificationBundle\DependencyInjection;

use JMS\JobQueueBundle\JMSJobQueueBundle;
use Kopay\NotificationBundle\Console\NotificationCommandInterface;
use Kopay\NotificationBundle\Job\JmsJobBundleProvider;
use Kopay\NotificationBundle\Server\Security\JwtAuthProvider;
use Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
final class KopayNotificationExtension extends Extension
{
    const METADATA_LISTENER = 'kopaygorodsky_notification.metadata_listener';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->validateSendingProviders($config, $container);
        $this->validateJobProvider($config, $container);
        $this->validateConsoleCommand($config, $container);
        $this->validatePushServerConfigs($config, $container);
        $this->validateRecipientClass($config, $container);
    }

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

    private function validateJobProvider(array $config, ContainerBuilder $container): void
    {
        $registry = $container->findDefinition('kopaygorodsky_notification.job_provider');

        if ($registry->getClass() === JmsJobBundleProvider::class) {
            if (!$this->isBundleEnabled('JMS\JobQueueBundle\JMSJobQueueBundle', $container)) {
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

    private function validatePushServerConfigs(array $config, ContainerBuilder $container): void
    {
        if (isset($config['types']['push']['server'])) {
            $serverConfig = $config['types']['push']['server'];

            if (true === $serverConfig['auth']) {
                if (!$this->isBundleEnabled('Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle', $container)) {
                    throw new \LogicException(
                        sprintf(
                            'Cannot register "%s" without "%s" registered.',
                            JwtAuthProvider::class,
                            'Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle'
                        )
                    );
                }
            } else {
                $container->removeDefinition('kopaygorodsky_notification.websockets.auth_provider');
            }

            if (false === $serverConfig['default']) {
                $container->removeDefinition('kopaygorodsky_notification.websocket_server');
                $container->removeDefinition('kopaygorodsky_notification.console.start_server');
                return;
            }

            $serverDefinition = $container->getDefinition('kopaygorodsky_notification.websocket_server');
            $serverDefinition->setArgument(0, $serverConfig['port']);

            if (true === $serverConfig['auth']) {
                $serverDefinition->setArgument(1, new Reference('kopaygorodsky_notification.websockets.auth_provider'));
            }
        }
    }

    private function validateRecipientClass(array $config, ContainerBuilder $container): void
    {
        if (isset($config['recipientClass']) && !empty($config['recipientClass'])) {
            $userClass = $config['recipientClass'];

            if (!class_exists($userClass)) {
                throw new \LogicException(sprintf('Recipient class %s does not exist', $userClass));
            }
            $listenerDefinition = $container->getDefinition(self::METADATA_LISTENER);
            $listenerDefinition->addArgument($userClass);
        }
    }

    /**
     * @param string $bundle
     * @param ContainerBuilder $container
     * @return bool
     */
    public function isBundleEnabled(string $bundle, ContainerBuilder $container): bool
    {
        return array_key_exists($bundle, array_flip($container->getParameter('kernel.bundles')));
    }
}
