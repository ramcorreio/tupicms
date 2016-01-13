<?php
namespace Internit\ContactBundle\Controller;

use Tupi\SecurityBundle\Entity\Setting;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\CrudController;
use Symfony\Component\HttpFoundation\Request;
use Internit\ContactBundle\Form\ContactConfigType;
use Internit\ContactBundle\Entity\ContactConfig;

class ContactConfigController extends CrudController
{
	
	const REPOSITORY_NAME = 'InternitContactBundle:ContactConfig';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitContactBundle:ContactConfig';
	
	protected $defaultRoute = 'tupi_contact_config';	
	
	
	public function createTypedForm($type)
	{
	    return $this->createForm(new ContactConfigType(), $type,  array(
            'attr' => array()
	    ));
	}
	
	protected function initObject($id = null) 
	{    
	   $setting = $this->getRepository()->findOneBy(array('id' => $id));
	    if(empty($setting)) {
	        $setting = new ContactConfig();
	    }
	    
	    return $setting;
	}
	
	protected function removed(ReturnVal $return)
	{
		
	}
	
	protected function save(ReturnVal $return, $id = null, $setting, $form, EntityManager $em)
	{
		if(is_null($setting->getId())) {
			$em->persist($setting);
			$return->setMessage('Definições cadastradas com sucesso!');
		}
		else {
			$em->merge($setting);
			$return->setMessage('Definições alteradas com sucesso!');
		}
    }
}
?> 