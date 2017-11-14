<?php

namespace Kopay\NotificationBundle\DependencyInjection;

use JMS\JobQueueBundle\JMSJobQueueBundle;
use Kopay\NotificationBundle\Console\NotificationCommandInterface;
use Kopay\NotificationBundle\Job\JmsJobBundleProvider;
use Kopay\NotificationBundle\Server\Security\AuthenticatorInterface;
use Kopay\NotificationBundle\Server\Security\JwtAuthProvider;
use Kopay\NotificationBundle\Server\ServerStackInterface;
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
    const METADATA_LISTENER = 'kopay_notify.metadata_listener';

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
                $container->removeDefinition('kopay_notify.sending_provider.email');
            }
            if (isset($config['types']['push']['default_provider']) && false === $config['types']['push']['default_provider']) {
                $container->removeDefinition('kopay_notify.sending_provider.push');
            }
        }

        $taggedServices = $container->findTaggedServiceIds('kopay_notify.sending_provider');
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
        $registry = $container->findDefinition('kopay_notify.job_provider');

        if ($registry->getClass() === JmsJobBundleProvider::class) {
            if (!$this->isBundleEnabled('JMS\JobQueueBundle\JMSJobQueueBundle', $container)) {
                throw new \LogicException(
                    sprintf(
                        'Cannot register "%s" without "%s" registered',
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
        $registry = $container->findDefinition('kopay_notify.console.send_notification');

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
                            'Cannot register "%s" without "%s" registered',
                            JwtAuthProvider::class,
                            'Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle'
                        )
                    );
                }

                $authProviderDefinition = $container->getDefinition('kopay_notify.websockets.auth_provider');

                if (!array_key_exists(AuthenticatorInterface::class, class_implements($authProviderDefinition->getClass()))) {
                    throw new \LogicException(sprintf('Authenticator service %s must implement %s', $authProviderDefinition->getClass(), AuthenticatorInterface::class));
                }

            } else {
                $container->removeDefinition('kopay_notify.websockets.auth_provider');
            }

            if (false === $serverConfig['default']) {
                $container->removeDefinition('kopay_notify.notification_server');
                $container->removeDefinition('kopay_notify.console.start_server');
                $container->removeDefinition('kopay_notify.server_stack');
                return;
            }

            $serverDefinition = $container->getDefinition('kopay_notify.notification_server');

            if (true === $serverConfig['auth']) {
                $serverDefinition->setArgument(0, new Reference('kopay_notify.websockets.auth_provider'));
            }

            $serverStackDefinition = $container->getDefinition('kopay_notify.server_stack');

            if (!array_key_exists(ServerStackInterface::class, class_implements($serverStackDefinition->getClass()))) {
                throw new \LogicException(sprintf('Server stack service %s must implement %s', $serverStackDefinition->getClass(), ServerStackInterface::class));
            }

            $serverStackDefinition->addArgument($serverConfig['port']);


            $startServerDefinition = $container->getDefinition('kopay_notify.console.start_server');

            $startServerDefinition->setArgument(0, new Reference('kopay_notify.server_stack'));

            if ($container->hasDefinition('kopay_notify.sending_provider.push')) {
                $container->getDefinition('kopay_notify.sending_provider.push')
                    ->addArgument($serverConfig['port']);
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
