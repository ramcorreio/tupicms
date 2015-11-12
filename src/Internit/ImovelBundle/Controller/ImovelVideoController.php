<?php

namespace Internit\ImovelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;

use Internit\ImovelBundle\Form\ImovelVideoType;
use Internit\ImovelBundle\Entity\ImovelVideo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImovelVideoController extends CrudController
{
	const REPOSITORY_NAME = 'InternitImovelBundle:ImovelVideo';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitImovelBundle:Video';
	
	protected $defaultRoute = 'imovel_video_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new ImovelVideoType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new ImovelVideo();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}   
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Vídeo excluído com sucesso!");
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Vídeo cadastrado com sucesso!');
        }
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Vídeo alterado com sucesso!');
        }
    }
}
