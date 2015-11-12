<?php
namespace Internit\TrabalheBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\TrabalheBundle\Form\SubjectType;
use Internit\TrabalheBundle\Form\GroupListType;
use Internit\TrabalheBundle\Entity\TrabalheSubject;
use Internit\TrabalheBundle\Entity\TrabalheSubjectGroup;
use Tupi\AdminBundle\Types\StatusType;

class SubjectController extends CrudController
{
    const REPOSITORY_NAME = 'InternitTrabalheBundle:TrabalheSubject';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitTrabalheBundle:Subject';
    
    protected $defaultRoute = 'tupi_subjectTrabalhe_home';
    
    protected function createTypedForm($type)
    {
    	return $this->createForm(new SubjectType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {
        $subject = new TrabalheSubject();
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