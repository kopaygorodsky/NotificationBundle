<?php

namespace Kopaygorodsky\NotificationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterTagServicesPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $registry;

    /**
     * @var string
     */
    private $tagName;

    /**
     * RegisterTagServicesPass constructor.
     * @param string $registry
     * @param string $tagName
     */
    public function __construct(string $registry, string $tagName)
    {
        $this->registry = $registry;
        $this->tagName = $tagName;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition($this->registry)) {
            return;
        }
        $registry = $container->findDefinition($this->registry);
        $taggedServices = $this->findReferences($container, $this->tagName);
        $registry->addArgument($taggedServices);
    }

    /**
     * Finds service definitions tagged by a given tag name.
     *
     * @param ContainerBuilder $container
     * @param string           $tagName
     *
     * @return Reference[]
     */
    private function findReferences(ContainerBuilder $container, string $tagName): array
    {
        $taggedServiceIds = $container->findTaggedServiceIds($tagName);
        $taggedReferences = [];
        foreach ($taggedServiceIds as $taggedServiceId => $tags) {
            $taggedReferences[] = new Reference($taggedServiceId);
        }

        return $taggedReferences;
    }
}