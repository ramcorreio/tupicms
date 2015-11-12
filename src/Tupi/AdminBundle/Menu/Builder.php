<?php
// src/Tupi/AdminBundle/Menu/Builder.php
namespace Tupi\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tupi\AdminBundle\Commom\Util;
use Knp\Menu\MenuItem;
use Tupi\SecurityBundle\Form\Authorization;
use Tupi\SecurityBundle\Form\PermissionHelper;
use Tupi\SecurityBundle\Entity\User;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;

class Builder extends ContainerAware
{
    private $factory;
    private $eventDispatcher;
    
    /**
     * @param FactoryInterface $factory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
    	$this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    /**
     * Criar o menu básico
     * 
     * @param Request $request
     * 
     * @return Menu
     */
    public function createMainMenu(Request $request)
    {
    	//Util::obterPermissao($path, $security);
    	    	
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
      
        $menu->addChild('Início', array('route' => 'tupi_admin'));
        
        //TODO: Montar sub-menu de conteúdo
        //$content = $menu->addChild('Conteúdo');
        //$content->setChildrenAttribute('class', 'submenu');
        $menu->addChild('Arquivos e Imagens', array('route' => 'tupi_media_home'));
        $menu->addChild('Páginas', array('route' => 'tupi_page_home'));
        $menu->addChild('Menu', array('route' => 'tupi_menu_home'));
        
        
        $modules = $menu->addChild('Módulos');
        $modules->setChildrenAttribute('class', 'submenu');
        
        //TODO: Essa estrutura de contatos e Blog não deve estar aqui e sim o site base para evitar problemas com os outros desenvolvedores.        
        //$contatos = $menu->addChild('Contatos');
        //$contatos->setChildrenAttribute('class', 'submenu');
        
        //$contatos->addChild('Assunto', array('route' => 'tupi_media_home'));
        //$contatos->addChild('Contatos', array('route' => 'tupi_media_home'));
        //$contatos->addChild('Cadastros', array('route' => 'tupi_media_home'));
        
        //$menu->addChild('Blog', array('route' => 'tupi_page_home'));
        
        $this->eventDispatcher->dispatch(BuildEvent::MENU_BUILDER, new BuildEvent($modules));
        $this->getChildren($menu);
        
        return $menu;
    }
    
    private function getChildren($item) {

	//TODO:Verificar $linksComuns

    	$linksComuns = Array(
    				'Início'
    			);
    	
    	 foreach ($item->getChildren() as $child){
    	 	$uri = $child->getUri();    

    	 	$permissao = $this->verificarPermissao($uri);

    	 	if($permissao == false && !in_array($child->getName(), $linksComuns))
    	 	{
    	 		$item->removeChild($child->getName());
    	 	}
    	 	
    		if(count($child->getChildren()) > 0) {    			
    			$this->getChildren($child); 
    			 if(count($child->getChildren()) == 0)
    			 {
    			 	$item->removeChild($child->getName());
    			 }
    		}
    	 }
    	
    } 
    
    //TODO: Verificar possibilidade de reuso da função
    public function verificarPermissao($path)
    {
    	
    	if($this->container->get('security.context')->getToken()->getUser()->getRole()=='ROLE_ADMIN')
    		return true;
    	
    	$url = Array();
    	$urlAtual = $path;
    	if($path==null)
    	{
    		return true;
    	}
    		preg_match("/admin(\/[a-z]{1,})?/", $urlAtual, $url);
    	
    	$id = $this->container->get('security.context')->getToken()->getUser()->getId();
    	$aclProvider = $this->container->get('security.acl.provider');
    	 
    	$resources = $this->container->get('doctrine')->getRepository('TupiSecurityBundle:Resource')->findAll();
    	 
    	$perm = new PermissionHelper();
    	$perm->setUser(new User());
    	 
    	$permissoes = array();
    	foreach ($resources as $resource) {
    		$auth = new Authorization();
    		$auth->setResource($resource);
    		$auth->setAuthorizations(array());
    		array_push($permissoes, $auth);
    	}
    	$perm->setPermissoes($permissoes);
    	$perm->setUser($this->container->get('doctrine')->getRepository('TupiSecurityBundle:User')->findOneBy(array('id' => $id)));
    
    	//var_dump($perm);
    	$securityIdentity = UserSecurityIdentity::fromAccount($perm->getUser());
    	$permissoes = $perm->getPermissoes();
    	
    	if(!empty($permissoes)) {
    		 
    		foreach ($permissoes as $permissao) {
    			 
    			$authorizations = array();
    			$objectIdentity = ObjectIdentity::fromDomainObject($permissao->getResource());
    			try {
    				$acl = $aclProvider->findAcl($objectIdentity, array($securityIdentity));
    				foreach($acl->getObjectAces() as $index => $ace) {
    						
    					if($securityIdentity->equals($ace->getSecurityIdentity())) {
    
    						$masker = new MaskBuilder();
    						foreach (Authorization::$AUTHORIZATIONS as $key => $label)
    						{
    								
    							$masker->reset();
    							$masker->add($key);
    							if(($ace->getMask() & $masker->get()) === 0) {
    								continue;
    							}
    								
    							array_push($authorizations, $key);
    						}
    					}
    				}
    
    				$permissao->setAuthorizations($authorizations);
    			}
    			catch (\Symfony\Component\Security\Acl\Exception\AclNotFoundException $e)
    			{
    				 
    			}
    		}
    	}
    	
    	$permitido = false;
    	//var_dump($perm->getPermissoes());
    	foreach($perm->getPermissoes() as $p)
    	{
    		if($url[0] == $p->getResource()->getPath())
    		{
    			if(count($p->getAuthorizations())>0)
    				$permitido = true;
    			//return true;
    
    		}
    	}
    	//var_dump($permitido);
    	return $permitido;
    	 
    }
     
}