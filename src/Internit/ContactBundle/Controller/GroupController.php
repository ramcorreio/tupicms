<?php
namespace Internit\ContactBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Form\GroupType;
use Internit\ContactBundle\Form\GroupEmailType;
use Internit\ContactBundle\Entity\ContactGroup;
use Internit\ContactBundle\Entity\ContactGroupEmail;
use Tupi\AdminBundle\Types\StatusType;

class GroupController extends CrudController
{
    const REPOSITORY_NAME = 'InternitContactBundle:ContactGroup';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitContactBundle:Group';
    
    protected $defaultRoute = 'tupi_group_home';

    protected function createTypedForm($type)
    {
    	return $this->createForm(new GroupType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {        
        $group = new ContactGroup();
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