<?php

namespace Belton\SimpleAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('belton_simple_admin');

        $rootNode
            ->children()
                ->scalarNode('website')
                    ->defaultValue('Your web site')
                ->end()
                ->scalarNode('backlink')
                    ->defaultValue('https://github.com/davidjegat/SimpleAdminBundle')
                ->end()
                ->arrayNode('menu')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('access')->isRequired()->cannotBeEmpty()->end()
                            ->variableNode('link')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('registration')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->arrayNode('entity')
                                ->isRequired()
                                ->children()
                                    ->scalarNode('class')->end()
                                    ->scalarNode('repository')->end()
                                    ->booleanNode('user_bundle')
                                        ->defaultFalse()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('form')
                                ->isRequired()
                                ->children()
                                    ->scalarNode('class')->end()
                                    ->scalarNode('service')->end()
                                ->end()
                            ->end()
                            ->arrayNode('access')
                                ->children()
                                    ->scalarNode('list')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('edit')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('create')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('delete')
                                        ->isRequired()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('menu')
                                ->children()
                                    ->scalarNode('refer')
                                        ->defaultValue('')
                                    ->end()
                                    ->variableNode('link')
                                        ->defaultValue('')
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('misc')
                                ->children()
                                    ->scalarNode('thumb')
                                        ->defaultValue('')
                                    ->end()
                                    ->booleanNode('display')
                                        ->defaultFalse()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();


        return $treeBuilder;
    }
}
