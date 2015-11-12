<?php
namespace Internit\ContactBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;
use Internit\ContactBundle\Form\SubjectType;
use Internit\ContactBundle\Form\GroupListType;
use Internit\ContactBundle\Entity\ContactSubject;
use Internit\ContactBundle\Entity\ContactSubjectGroup;
use Tupi\AdminBundle\Types\StatusType;

class SubjectController extends CrudController
{
    const REPOSITORY_NAME = 'InternitContactBundle:ContactSubject';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitContactBundle:Subject';
    
    protected $defaultRoute = 'tupi_subject_home';
    
    protected function createTypedForm($type)
    {
    	return $this->createForm(new SubjectType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {
        $subject = new ContactSubject();
        if(!empty($id)) {
            $subject = $this->getRepository()->findOneBy(array('id' => $id));
        }
        
        return $subject;
    }   
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        //criar um novo assunto
        if(is_null($obj->getId())) {
            $em->persist($obj);
            $return->setMessage('Assunto cadastrado com sucesso!');
        }
        //alterar
        else {
            $em->merge($obj);
            $return->setMessage('Assunto alterado com sucesso!');
        }
    }
}