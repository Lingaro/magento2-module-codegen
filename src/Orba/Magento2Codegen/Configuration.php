<?php

namespace Orba\Magento2Codegen;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('codegen');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('defaultProperties')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('value')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templateDirectories')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('path')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}