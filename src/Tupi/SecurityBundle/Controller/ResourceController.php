<?php
namespace Tupi\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tupi\SecurityBundle\Entity\Resource;
use Tupi\SecurityBundle\Form\ResourceType;
use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\CrudController;

class ResourceController extends CrudController
{
	const REPOSITORY_NAME = 'TupiSecurityBundle:Resource';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'TupiSecurityBundle:Resource';
	
	protected $defaultRoute = 'tupi_resource_home';
	
	public function createTypedForm($type)
	{
	    return $this->createForm(new ResourceType(), $type,  array(
            'attr' => array()
	    ));
	}
	
	protected function initObject($id = null) 
	{
	    $r = new Resource();
	    if(!empty($id)) {
	        $r = $this->getRepository()->findOneBy(array('id' => $id));
	    }
	    
	    return $r;
	}
	
	protected function removed(ReturnVal $return)
	{
		//TODO: não implementado
	}
	
	protected function save(ReturnVal $return, $id = null, $r, $form, EntityManager $em)
	{
	    //criar um novo recurso
		if(is_null($r->getId())) {
			$r->setStatus(true);
			$em->persist($r);
			$return->setMessage('Funcionalidade cadastrada com sucesso!');
		}
		//alterar
		else {
			$em->merge($r);
			$return->setMessage('Funcionalidade alterada com sucesso!');
		}
    }
}