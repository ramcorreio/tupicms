<?php

namespace Internit\ImovelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;

use Internit\ImovelBundle\Form\ImovelTagType;
use Internit\ImovelBundle\Entity\ImovelTag;

class ImovelTagController extends CrudController
{
	const REPOSITORY_NAME = 'InternitImovelBundle:ImovelTag';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitImovelBundle:Tag';
	
	protected $defaultRoute = 'imovel_tag_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new ImovelTagType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new ImovelTag();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}   
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Tag excluída com sucesso!");
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Tag cadastrada com sucesso!');
        }
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Tag alterada com sucesso!');
        }
    }
}
