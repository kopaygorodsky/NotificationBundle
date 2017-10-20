<?php

namespace Kopay\NotificationBundle\DependencyInjection;

use JMS\JobQueueBundle\JMSJobQueueBundle;
use Kopay\NotificationBundle\KopayNotificationBundle;
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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $bundles = array_flip($container->getParameter('kernel.bundles'));

        if (true === $config['use_jms_job_bundle'] && false === array_key_exists(JMSJobQueueBundle::class, $bundles)) {
            throw new \LogicException(
                sprintf(
                    'Cannot register "%s" without "%s when use_jms_job_bundle is true".',
                    KopayNotificationBundle::class,
                    JMSJobQueueBundle::class
                )
            );
        }
    }
}
