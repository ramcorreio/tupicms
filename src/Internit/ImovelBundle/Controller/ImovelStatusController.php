<?php

namespace Internit\ImovelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;

use Internit\ImovelBundle\Form\ImovelStatusType;
use Internit\ImovelBundle\Entity\ImovelStatus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImovelStatusController extends CrudController
{
	const REPOSITORY_NAME = 'InternitImovelBundle:ImovelStatus';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitImovelBundle:Status';
	
	protected $defaultRoute = 'imovel_status_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new ImovelStatusType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new ImovelStatus();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}   
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Status excluído com sucesso!");
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Status cadastrado com sucesso!');
        }
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Status alterado com sucesso!');
        }
    }
}
