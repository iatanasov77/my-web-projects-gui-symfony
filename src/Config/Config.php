<?php namespace App\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Config implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder( 'vs_myprojects' );
        
        $rootNode   = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('auto_connect')
                ->defaultTrue()
                ->end()
                
                ->scalarNode('default_connection')
                ->defaultValue('default')
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
