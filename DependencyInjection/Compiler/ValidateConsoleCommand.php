<?php

namespace Kopay\NotificationBundle\DependencyInjection\Compiler;

use Kopay\NotificationBundle\Console\NotificationCommandInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ValidateConsoleCommand implements CompilerPassInterface
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

        if (!array_key_exists(NotificationCommandInterface::class, class_implements($registry->getClass()))) {
            throw new \LogicException(sprintf('Console command %s must implement %s', $registry->getClass(), NotificationCommandInterface::class));
        }
    }
}