<?php

namespace Tupi\SecurityBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Session\Session;

use Tupi\SecurityBundle\Entity\User;
use Tupi\SecurityBundle\Form\UserType;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\SecurityBundle\Form\PermissionHelperType;
use Tupi\SecurityBundle\Form\PermissionHelper;
use Tupi\SecurityBundle\Form\Authorization;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Tupi\SecurityBundle\Controller\UserController;
use Tupi\ContentBundle\Entity\Page;
use Tupi\SecurityBundle\Entity\ResourceRepository;
use Tupi\SecurityBundle\Controller\ResourceController;
use Tupi\SecurityBundle\Entity\Resource;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;

class AdminSecurityListener
{
	
    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
        	
        	$urlAtual = $event->getRequest()->getRequestUri();
        	preg_match("/admin(\/[a-z]{1,})?/", $urlAtual, $url);
        	
        	if(!$url)        	
        		return false;
        	
	        	$controller = $event->getController();
	        	
	        	$rObj = new \ReflectionObject($controller[0]);
	        	if(!$rObj->hasMethod("getDoctrine")) {
	        		return;
	        	}
	        	
	        	$menus = $controller[0];
	        	
	        	if($menus->get('security.context')->getToken()->getUser()!='anon.')
	        	{
	        		if($menus->get('security.context')->getToken()->getUser()->getRole()=='ROLE_ADMIN')
	        			return 'ROLE_ADMIN';
	        		
	        		$id = $menus->get('security.context')->getToken()->getUser()->getId();
	        	}
	        	
	        	$resources = $menus->getDoctrine()->getRepository('TupiSecurityBundle:Resource')->findAll();
	        	
	        	
	        	
	        	//init empty vars
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
	
			    if(!empty($id)) {
			    	$perm->setUser($menus->getDoctrine()->getRepository('TupiSecurityBundle:User')->findOneBy(array('id' => $id)));
			    	//$menus->carregarPermissoes($perm);
			    	$aclProvider = $menus->get('security.acl.provider');
			    	
			    	
			    	
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
			    }
			    $linksComuns = $menus->get('tupi_security.links_comum')->getParameters();

			    $permissaoUsuario = $this->verificarAutorizacao($perm, $linksComuns, $url[0]);
			    
			    if($permissaoUsuario==false)
			    	throw new Exception("Usuário sem acesso!!!");
	        	else  
	        		return $permissaoUsuario;
	        		
	        
        }
    }
    
    private function verificarAutorizacao($perm, $linksComuns,$url)
    {
    	$caminhos  = array();
    	foreach($perm->getPermissoes() as $p)
    	{
    		$caminhos[] = $p->getResource()->getPath();
    	}
    	
    	
    	if(in_array($url, $caminhos))
    	{
    		foreach($perm->getPermissoes() as $p)
    		{
    			$permissoes[$p->getResource()->getPath()] = $p->getAuthorizations();
    		}
    			
    		if(count($permissoes[$url])>0)
    			return $permissoes[$url];
    		else 
    			return false;
    	}
    	elseif(in_array($url, $linksComuns))
    		return true;
    	else
    		return false;
    }
}


