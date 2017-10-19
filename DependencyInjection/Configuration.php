<?php

namespace Kopaygorodsky\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kopaygorodsky_notification');

        $rootNode
            ->children()
                ->booleanNode('use_jms_job_bundle')
                    ->info('If true - JmsJobsQueueBundle is required')
                    ->defaultTrue()
                ->end()
                ->booleanNode('use_default_sockets_provider')
                    ->defaultTrue()
                ->end()
                ->booleanNode('use_default_email_provider')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
