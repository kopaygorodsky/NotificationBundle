<?php

namespace Kopay\NotificationBundle\DependencyInjection\Compiler;


use JMS\JobQueueBundle\JMSJobQueueBundle;
use Kopay\NotificationBundle\Job\JmsJobBundleProvider;
use Kopay\NotificationBundle\KopayNotificationBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ValidateJobProvider implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $registry;

    /**
     * RegisterTagServicesPass constructor.
     * @param string $registry
     */
    public function __construct(string $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        $registry = $container->findDefinition($this->registry);

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
}