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
                // Global configuration about the simple admin
                ->scalarNode('website')
                    ->defaultValue('Your web site')
                ->end()
                ->scalarNode('backlink')
                    ->defaultValue('https://github.com/davidjegat/SimpleAdminBundle')
                ->end()
                ->append($this->addMenuParameters())
                ->append($this->addRegistrationParameters())
                ->append($this->addFormParameters())
            ->end();


        return $treeBuilder;
    }

    /**
     * Add the Simple admin menu parameters :
     * 
     * @return TreeBuidler
     */
    public function addMenuParameters(){
        $builder = new TreeBuilder();
        // Defined the menu node :
        return $builder->root('menu')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('access')->isRequired()->cannotBeEmpty()->end()
                    ->arrayNode('link')
                        ->isRequired()
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function($v){ return array($v, array()); })
                        ->end()
                        ->prototype('variable')->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Add the Registration parameters
     * 
     * @return TreeBuilder
     */
    public function addRegistrationParameters(){
        $builder = new TreeBuilder();
        // Construct the registration node :
        return $builder->root('registration')
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
            ->end();
    }

    /**
     * Add the form services definitions parameters
     * 
     * @return TreeBuilder
     */
    public function addFormParameters(){
        $builder = new TreeBuilder();
        // Let's get it started ;) !
        return $builder->root('forms')
            ->children()
                ->arrayNode('crop')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('aspect_ratio')->end()
                            ->arrayNode('min_size')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('max_size')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('set_select')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('bg_opacity')->end()
                            ->scalarNode('bg_color')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
