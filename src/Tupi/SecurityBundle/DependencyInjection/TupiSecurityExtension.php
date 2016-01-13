<?php

namespace Tupi\SecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
	 Symfony\Component\Config\FileLocator,
	 Symfony\Component\HttpKernel\DependencyInjection\Extension,
	 Symfony\Component\DependencyInjection\Loader,
	 Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class TupiSecurityExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
    	$loader->load('services.xml');
    	
    	$configuration = new Configuration();
    	
    	$config = $this->processConfiguration($configuration, $configs);
    	$container->setParameter('tupi_security.links_comum.params', $config["links_comuns"]);
    	//var_dump($container->getParameter('tupi_security.links_comum.params')); 
    	
    }
}
