<?php

namespace Internit\ImovelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;

use Internit\ImovelBundle\Form\ImovelMakersType;
use Internit\ImovelBundle\Entity\ImovelMakers;
use Internit\ImovelBundle\Form\CropCollection;
use Internit\ImovelBundle\Form\Crop;
use Internit\ImovelBundle\Entity\ImovelImageMedia;
use Internit\ImovelBundle\Form\UploadImageHelper;

class ImovelMakersController extends CrudController
{
	const REPOSITORY_NAME = 'InternitImovelBundle:ImovelMakers';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitImovelBundle:Makers';
	
	protected $defaultRoute = 'imovel_makers_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new ImovelMakersType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new ImovelMakers();
		if(!empty($id)) {
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}   
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Realizador excluído com sucesso!");
	}
	
	private function setLogo($form, $obj, $em){
	
	    $file = $form['logo']['imagem']->getData();
	
	    if(!empty($file))
	    {
	        $cropCollection = new CropCollection();
	        $cropCollection->add('admin', new Crop('admin', 200, 200));
	        $cropCollection->add('destaque', new Crop('destaque', 180, 180, false));
	        $cropCollection->add('realizador_acompanhamento', new Crop('realizador_acompanhamento', 75, 48, false));
	
	        $image = new ImovelImageMedia();
	
	        $upload = new UploadImageHelper();
	        $upload->setFile($file);
	        $upload->setCrops($cropCollection);
	        $upload->setImage($image);
	
	        if($upload->doUpload() && $upload->isImage())
	        {
	            $upload->createThumbs();
	            $image = $upload->getImage();
	        }
	
	        $em->persist($image);
	
	        $obj->setLogo($image);
	    }
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        $this->setLogo($form, $obj, $em);
        
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Realizador cadastrado com sucesso!');
        }
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Realizador alterado com sucesso!');
        }
    }
}
