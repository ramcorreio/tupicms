<?php
namespace Tupi\ContentBundle\Twig;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

use Tupi\ContentBundle\Types\PageStatusType;
use Tupi\ContentBundle\Controller\MediaController;
use Tupi\ContentBundle\Controller\PageController;
use Tupi\ContentBundle\Controller\MenuController;
use Tupi\SecurityBundle\Controller\SettingController;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tupi\ContentBundle\Entity\Menu;
use Tupi\ContentBundle\Entity\Page;


class ContentExtension extends Twig_Extension
{
	private $container;
	
	//private $doctrine;
	
	/**
	 * Constructor.
	 *
	 * @param ContainerInterface $container The service container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->doctrine = $this->container->get('doctrine');
	}
	
	public function getFunctions()
	{
	    return array(
            new Twig_SimpleFunction('siteHome', array($this, 'getSiteHome'), array('is_safe' => array('html'))),
    		new Twig_SimpleFunction('getTupiTemplate', array($this, 'getTemplateRender'), array('is_safe' => array('html'))),
	    	new Twig_SimpleFunction('page', array($this, 'getPage'), array('is_safe' => array('html'))),
	    	new Twig_SimpleFunction('settings', array($this, 'getSettings'), array('needs_context' => true))
        );
	}
	
    public function getFilters()
    {
    	return array(
    	    new Twig_SimpleFilter('page_status', array($this, 'pageStatus')),
    		new Twig_SimpleFilter('get_image', array($this, 'getImage'))
    	);
    }
    
    public function getTemplateRender(Renderer $renderer)
    {
    	return Util::buildTemplate($renderer);
    }
    
    public function getPage($key)
    {
    	return $this->doctrine->getRepository(PageController::REPOSITORY_NAME)->getPageFromUrl($key);
    }
    
    public function getSiteHome()
    {
    	return $this->doctrine->getRepository(MenuController::REPOSITORY_NAME)->getRoot();
    }
    
    public function getSetting()
    {
    	return $this->doctrine->getRepository(SettingController::REPOSITORY_NAME)->findOneBy(array('id' => 1));
    }
    
    public function getSettings($context)
    {
    	$return = array('title' => "",'description' => "",'keyworks' => "",'ga' => "",'og_site_name' => "",'og_title' => "",'og_image' => "",'og_link' => "",'og_description' => "", 'og_id' => '');
    	$setting = $this->getSetting();

    	if(isset($context['session']) && !isset($context['status_code']))
    	{
    		$session = $context['session'];
    		if($session instanceof Page)
    		{
    			$return['title'] = $context['session']->getTitle();
    			$return['description'] = $session->getDescription();
    			$return['keyworks'] = $session->getMetaKeywords();
    		}
    		else if($session instanceof Page)
    		{
    			$return['title'] = $context['session']->getTitle();
    			$return['description'] = $session->getDescription();
    			$return['keyworks'] = $session->getMetaKeywords();
    		}
    		else 
    		{
    			if(!empty($setting))
    			{
    				$return['title'] = $setting->getTitle();
    				$return['description'] = $setting->getDescription();
    				$return['keyworks'] = $setting->getMetaKeywords();
    			}
    			
    			if($session instanceof Menu && !is_null($session->getParent()))
    			{
    				$return['title'] = $context['session']->getTitle();
    			}
    		}		
    	}
    	else
    	{
    		$return['title'] = 'Tupi CMS ';
    		if(isset($context['title_controle']))
    			$return['title'] .= " - " . $context['title_controle'];
    		$return['description'] = 'Sistema de Gerenciamento de Conteúdo, CMS';
    		$return['keyworks'] = 'Sistema de Gerenciamento de Conteúdo, CMS';
    	}   	
    	
    	if(!empty($setting)){
	    	$return['ga'] = $setting->getGa();
	    	$return['og_site_name'] = $setting->getFbSiteName();
	    	$return['og_title'] = $setting->getFbTitle();
	    	$return['og_image'] = $setting->getFbImage();
	    	$return['og_link'] = $setting->getFbLink();
	    	$return['og_description'] = $setting->getFbDescription();
	    	$return['og_id'] = $setting->getFbId();
    	}
    	
    	if($this->container->getParameter('kernel.environment') == 'dev') {
    		
    		unset($return['ga']);
    	}
    	
    	return $return;
    }

	public function pageStatus($status)
    {
    	return PageStatusType::getTypeLabel($status);
    }
    
   	public function getImage($formView)
    {
    	$doctrine = $this->container->get('doctrine');
    	$repo = $doctrine->getManager()->getRepository(MediaController::REPOSITORY_NAME);
    	$media = $repo->findOneBy(array('id' => $formView->vars['value']));
    	
    	$formView->vars['data'] = $media;
    }

    public function getName()
    {
        return 'content_extension';
    }
}