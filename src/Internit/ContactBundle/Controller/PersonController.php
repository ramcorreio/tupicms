<?php
namespace Internit\ContactBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;
use Internit\ContactBundle\Form\PersonType;

class PersonController extends CrudController
{
    const REPOSITORY_NAME = 'InternitContactBundle:ContactPerson';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitContactBundle:Person';
    
    protected $defaultRoute = 'tupi_person_home';
    
    protected function createTypedForm($type)
    {
        return $this->createForm(new PersonType(), $type);
    }   

    protected function listItens($key = 'item') {
    	return $this->getRepository()->listItens($key);
    }
    
    protected function initObject($id = null) 
    {
        $person = $this->getRepository()->findOneBy(array('id' => $id));
        
        return $person;
    }
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
       /*  $obj->setCreatedAt(new \DateTime());
        $obj->getRequest()->setUpdatedAt($obj->getCreatedAt());
        $em->merge($obj);
        $return->setMessage("Contato respondido com sucesso!"); */
    }
}