<?php

namespace Tupi\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\ContentBundle\Entity\Media;
use Tupi\ContentBundle\Form\MediaType;
use Tupi\ContentBundle\Entity\ImageMedia;
use Tupi\ContentBundle\Form\MediaFileType;
use Tupi\ContentBundle\Form\UploadHelper;
use Tupi\ContentBundle\Form\UploadHelperType;
use Tupi\AdminBundle\Types\StatusType;
use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;

class MediaController extends CrudController
{
	const REPOSITORY_NAME = 'TupiContentBundle:ImageMedia';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'TupiContentBundle:Media';
	
	protected $defaultRoute = 'tupi_media_home';
	
	protected function createTypedForm($type)
	{
		//Criado para usar o base controler com formulários customizados.
		return $this->createForm(new UploadHelperType(), $type);
	}
	
	protected function initObject($id = null) {
		
		$upload = new UploadHelper();
		$upload->setMedia(new ImageMedia());
		if(!empty($id)) {
				
			$upload->setMedia($this->getRepository()->findOneBy(array('id' => $id)));
		}
		
		return $upload;
	}
	
	protected function removeall(ReturnVal $return, $id)
	{
	    $qtd = parent::removeall($return, $id);
	    if($qtd > 1) {
	    	$return->setMessage("Medias excluídas com sucesso!");
	    }   
	    else {
	    	$this->removed($return);
	    } 
	}
	
	protected function removed(ReturnVal $return)
	{
	    $return->setMessage("Media excluída com sucesso!");
	}
	
	protected function save(ReturnVal $return, $id = null, $upload, $form, EntityManager $em)
	{
		if($upload->doUpload() && $upload->isImage()) 
		{	
			$upload->thumbnail();
		}
		
		//criar um novo recurso
		if(is_null($upload->getMedia()->getId())) {
			$em->persist($upload->getMedia());
		}
		//alterar
		else {
			$upload->getMedia()->setUpdatedAt(new \DateTime());
			$em->merge($upload->getMedia());
		}
	}
	
	public function imagesAction()
	{
	    $images = $this->getRepository()->findByMimeType('image');
	    return $this->render($this->bundleName . ':images.html.twig', array('images' => $images));
	}
	
	public function imageAction($url, $ext, $crop)
	{
	    $response = new Response();
	    if ($response->isNotModified($this->getRequest())) {
	        return $response;
	    }else{
	        $image = $this->getRepository()->findOneBy(array('url' => $url));
	
	        if (!$image) {
	            throw $this->createNotFoundException('A imagem não existe');
	        }
	
	        if($crop == "thumb")
	            $base64 = $image->getThumb();
	        else
	            $base64 = $image->getBin()->getValue();
	
	        $stream = fopen("data:".$image->getMimeType().";base64,". $base64 , 'r');
	        $response = new Response(stream_get_contents($stream), 200);
	        $response->headers->set('Content-Type', $image->getMimeType());
	        $response->setLastModified($image->getUpdatedAt());
	
	        return $response;
	    }
	}
}
