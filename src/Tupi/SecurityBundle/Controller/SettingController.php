<?php
namespace Tupi\SecurityBundle\Controller;

use Tupi\SecurityBundle\Form\SettingType;
use Tupi\SecurityBundle\Entity\Setting;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\CrudController;

class SettingController extends CrudController
{
	const REPOSITORY_NAME = 'TupiSecurityBundle:Setting';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'TupiSecurityBundle:Setting';
	
	protected $defaultRoute = 'tupi_setting_home';
	
	public function createTypedForm($type)
	{
	    return $this->createForm(new SettingType(), $type,  array(
            'attr' => array()
	    ));
	}
	
	protected function initObject($id = null) 
	{    
	   $setting = $this->getRepository()->findOneBy(array('id' => $id));
	    if(empty($setting)) {
	        $setting = new Setting();
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