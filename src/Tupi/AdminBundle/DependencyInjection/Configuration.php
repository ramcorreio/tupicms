<?php
namespace Tupi\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('tupi_admin');
		
		$rootNode
			->children()
				->arrayNode('enderecos')
					->useAttributeAsKey('name')
					->prototype('array')
						->children()
							->scalarNode('logradouro')->isRequired()->end()
							->scalarNode('bairro')->isRequired()->end()
							->scalarNode('cidade')->isRequired()->end()
							->scalarNode('uf')->isRequired()->end()
						->end()
					->end()
				->end()
				->arrayNode('telefones')
					->prototype('scalar')->end()
				->end()
				->arrayNode('emails')
					->prototype('scalar')->end()
				->end()
				->arrayNode('error')->isRequired()
					->useAttributeAsKey('name')
					->prototype('array')
						->children()
							->scalarNode('notFound')->isRequired()->end()
							->scalarNode('fatalError')->isRequired()->end()
						->end()
					->end()
				->end()
			->end()
		;

		return $treeBuilder;
	}
}