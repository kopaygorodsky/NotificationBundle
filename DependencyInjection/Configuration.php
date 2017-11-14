<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\DependencyInjection;

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
        $rootNode    = $treeBuilder->root('kopay_notify')->isRequired();

        $rootNode
            ->children()
                ->scalarNode('recipientClass')
                    ->end()
                ->arrayNode('types')
                    ->children()
                        ->arrayNode('email')
                            ->children()
                                ->booleanNode('default_provider')
                                    ->defaultTrue()
                                    ->end()
                            ->end()
                        ->end()
                        ->arrayNode('push')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('default_provider')
                                ->defaultTrue()
                                ->end()
                            ->arrayNode('server')
                                ->children()
                                    ->booleanNode('default')
                                        ->defaultTrue()
                                    ->end()
                                    ->scalarNode('port')
                                        ->defaultValue(8080)
                                    ->end()
                                    ->booleanNode('auth')
                                        ->defaultFalse()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
