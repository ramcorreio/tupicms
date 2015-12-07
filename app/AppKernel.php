<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
    	$bundles = array(
			
    		new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
    		new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
    		new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    			
    		new Tupi\AdminBundle\TupiAdminBundle(),
    		new Tupi\SecurityBundle\TupiSecurityBundle(),
    		new Tupi\ContentBundle\TupiContentBundle(),
    			
    		new Internit\SiteBaseBundle\InternitSiteBaseBundle(),
    		new Internit\ContactBundle\InternitContactBundle(),
    		new Internit\OferecaBundle\InternitOferecaBundle(),
            new Internit\RandomChatBundle\InternitRandomChatBundle(),
            new Internit\AcompanhamentoBundle\InternitAcompanhamentoBundle(),
    		new Internit\ImovelBundle\InternitImovelBundle(),
            new Internit\NewsletterBundle\InternitNewsletterBundle(),
    	    new Internit\InteressadoBundle\InternitInteressadoBundle(),
    		new Internit\BannerBundle\InternitBannerBundle(),
    		new Internit\TrabalheBundle\InternitTrabalheBundle(),
    	    new Internit\AtasBundle\InternitAtasBundle(),
    	    
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    	$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
