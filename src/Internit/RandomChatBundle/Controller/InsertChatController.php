<?php
namespace Internit\RandomChatBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\RandomChatBundle\Form\RandomChatLinkType;
use Internit\RandomChatBundle\Entity\RandomChatLinks;


class InsertChatController extends CrudController
{
    const REPOSITORY_NAME = 'InternitRandomChatBundle:RandomChatLinks';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitRandomChatBundle:RandomChat';
    
    protected $defaultRoute = 'randomchat_home';
    
    protected function createTypedForm($type)
    {
    	return $this->createForm(new RandomChatLinkType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {        
        $chat = new RandomChatLinks();
        if(!empty($id)) {
        
            $chat = $this->getRepository()->findOneBy(array('id' => $id));
        }
        
        return $chat;
    }
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        if(is_null($obj->getId()))
        {
            $obj->setCreatedAt(new \DateTime());
            $obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage("Chat salvo com sucesso!");
        }else{
            $obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage("Chat alterado com sucesso!"); 
        }
    }
}