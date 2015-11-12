<?php
namespace Internit\TrabalheBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\TrabalheBundle\Form\GroupType;
use Internit\TrabalheBundle\Form\GroupEmailType;
use Internit\TrabalheBundle\Entity\TrabalheGroup;
use Internit\TrabalheBundle\Entity\TrabalheGroupEmail;
use Tupi\AdminBundle\Types\StatusType;

class GroupController extends CrudController
{
    const REPOSITORY_NAME = 'InternitTrabalheBundle:TrabalheGroup';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitTrabalheBundle:Group';
    
    protected $defaultRoute = 'tupi_groupTrabalhe_home';

    protected function createTypedForm($type)
    {
    	return $this->createForm(new GroupType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {        
        $group = new TrabalheGroup();
        if(!empty($id) AND $id != "null") {
        
            $group = $this->getRepository()->findOneBy(array('id' => $id));
        }
        
        return $group;
    }
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        //criar um novo assunto
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Grupo cadastrada com sucesso!');
        }
        //alterar
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Grupo alterado com sucesso!');
        }
    }
}