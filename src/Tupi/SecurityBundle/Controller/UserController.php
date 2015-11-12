<?php

namespace Tupi\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tupi\SecurityBundle\Entity\User;
use Tupi\SecurityBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\SecurityBundle\Form\PermissionHelperType;
use Tupi\SecurityBundle\Form\PermissionHelper;
use Tupi\SecurityBundle\Form\Authorization;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Core\Util\ClassUtils;
use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;

class UserController extends CrudController
{
	const REPOSITORY_NAME = 'TupiSecurityBundle:User';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'TupiSecurityBundle:User';
	
	protected $defaultRoute = 'tupi_user_home';
	
	public function createTypedForm($type)
	{
		return $this->createForm(new PermissionHelperType($this, $this->getRepository()), $type);
	}
	
	protected function initObject($id = null) {
	    
	    //init empty vars
	    $perm = new PermissionHelper();
	    $perm->setUser(new User());
	    $this->setRole($perm->getUser());
	    
	    $resources = $this->getRespositoryName(ResourceController::REPOSITORY_NAME)->findAll();
	     
	    $permissoes = array();
	    foreach ($resources as $resource) {
	        $auth = new Authorization();
	        $auth->setResource($resource);
	        $auth->setAuthorizations(array());
	        array_push($permissoes, $auth);
	    }
	     
	    $perm->setPermissoes($permissoes);
	    
	    //load dada
	    if(!empty($id)) {
	        	
	        $perm->setUser($this->getRepository()->findOneBy(array('id' => $id)));
	        $this->carregarPermissoes($perm);
	    }
	    
	    return $perm;
	}
	
	public function perfilAction($id) {
		
		$this->editable = true;
		$this->cadastroTemplate = 'user.html.twig';
		
		$perfil = $this->doAction('edit', $id);
		if($perfil instanceof RedirectResponse) {
			return $this->redirect($this->generateUrl('tupi_user_perfil', array('id' => $id)));
		}
			
		return $perfil;
	}
	
	protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
	{
	    $perm = $form->getData();
	    $this->getRepository()->setContainer($this->container);
        
		//criar um novo usuário
		if(is_null($perm->getUser()->getId())) {
			
			$this->getRepository()->encryptPass($perm->getUser());
			$this->getRepository()->persist($perm->getUser());
			$return->setMessage('Usuário cadastrado com sucesso!');
		}
		//alterar sem alterar senha
		else if(is_null($perm->getUser()->getPassword())) {
			
		    $userPass = $em->createQuery("select u.password from TupiSecurityBundle:User u where u.id = " . $perm->getUser()->getId())->getSingleResult();
		    
		    //recupar senha e salt em caso de alteração sem modificar senha
			$perm->getUser()->setPassword($userPass['password']);
			
			$this->getRepository()->update($perm->getUser());
			$return->setMessage('Usuário alterado com sucesso!');
		}
		//alterar com alteração de senha
		else {
			$this->getRepository()->encryptPass($perm->getUser());
			$this->getRepository()->update($perm->getUser());
			$return->setMessage('Senha alterada com sucesso!');
		}
		
		$this->tratarPermissoes($perm);
	}
	
	private function carregarPermissoes(PermissionHelper $perm) {

		$aclProvider = $this->get('security.acl.provider');
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
				catch (AclNotFoundException $e)
				{
					//nada se faz nada
				}
				
				
			}
		}
	}
	
	private function tratarPermissoes(PermissionHelper $perm) {
		
		$aclProvider = $this->get('security.acl.provider');
		$securityIdentity = UserSecurityIdentity::fromAccount($perm->getUser());
		
		$permissoes = $perm->getPermissoes();
		if(!empty($permissoes)) {
			
			foreach ($permissoes as $permissao) {
				
				$builder = new MaskBuilder();
				foreach ($permissao->getAuthorizations() as $aut) 
				{
					$builder->add($aut);
				}
				
				$objectIdentity = ObjectIdentity::fromDomainObject($permissao->getResource());
				
				$acl = null;
				try {
					$acl = $aclProvider->findAcl($objectIdentity, array($securityIdentity));
					foreach($acl->getObjectAces() as $index => $ace) {
						
						if(!$securityIdentity->equals($ace->getSecurityIdentity())) {
							continue;
						}
						
						$acl->deleteObjectAce($index);
					}
				}
				catch (AclNotFoundException $e) 
				{
					$acl = $aclProvider->createAcl($objectIdentity);
				}
				
				$acl->insertObjectAce($securityIdentity, $builder->get());
				$aclProvider->updateAcl($acl);
			}
		}
	}
	
	private function setRole(User $user) {
		$user->setActive(true);
		$user->setRole('ROLE_USER');
	}    
}