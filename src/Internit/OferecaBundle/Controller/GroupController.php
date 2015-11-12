<?php
namespace Internit\OferecaBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\OferecaBundle\Form\GroupType;
use Internit\OferecaBundle\Form\GroupEmailType;
use Internit\OferecaBundle\Entity\OferecaGroup;
use Internit\OferecaBundle\Entity\OferecaGroupEmail;
use Tupi\AdminBundle\Types\StatusType;

class GroupController extends CrudController
{
    const REPOSITORY_NAME = 'InternitOferecaBundle:OferecaGroup';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitOferecaBundle:Group';
    
    protected $defaultRoute = 'tupi_groupOfereca_home';

    protected function createTypedForm($type)
    {
    	return $this->createForm(new GroupType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {        
        $group = new OferecaGroup();
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