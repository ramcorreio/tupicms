<?php

namespace Internit\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;

use Internit\NewsletterBundle\Form\NewsletterType; //form
use Internit\NewsletterBundle\Entity\Newsletter; //entity

class NewsletterController extends CrudController
{
	const REPOSITORY_NAME = 'InternitNewsletterBundle:Newsletter';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitNewsletterBundle:Newsletter';
	
	protected $defaultRoute = 'newsletter_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new NewsletterType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new Newsletter();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        //criar um novo assunto
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Newsletter cadastrado com sucesso!');
        }
        //alterar
        else {
            $em->merge($obj);
            $return->setMessage('Newsletter alterado com sucesso!');
        }
    }
}