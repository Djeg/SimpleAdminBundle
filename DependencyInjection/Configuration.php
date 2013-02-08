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
                            ->scalarNode('entity_class')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('repository')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('form')->end()
                            ->scalarNode('form_service')->end()
                            ->arrayNode('actions')
                                ->children()
                                    ->variableNode('edit')->end()
                                    ->variableNode('create')->end()
                                    ->variableNode('delete')->end()
                                    ->variableNode('list')->end()
                                ->end()
                            ->end()
                            ->scalarNode('image')->end()
                            ->variableNode('display')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();


        return $treeBuilder;
    }
}
