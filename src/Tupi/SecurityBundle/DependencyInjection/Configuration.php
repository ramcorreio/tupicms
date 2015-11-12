<?php

namespace Tupi\SecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
	Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('tupi_security');
        $node
	        ->children()
		        ->arrayNode('links_comuns')
			        ->prototype('scalar')
			        ->end()
		        ->end()
	        ->end()
        ;
        
        return $treeBuilder;
    }
}
