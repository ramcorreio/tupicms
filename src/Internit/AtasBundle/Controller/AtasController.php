<?php

namespace Internit\AtasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\HttpFoundation\Response;

use Tupi\ContentBundle\Form\UploadHelper;
use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;

use Doctrine\ORM\EntityManager;

use Internit\AtasBundle\Form\AtasType; //form
use Internit\AtasBundle\Entity\Atas; //entity
use Internit\AtasBundle\Entity\AtasArquivo;
use Internit\AtasBundle\Form\UploadAtasHelper;


class AtasController extends CrudController
{
	const REPOSITORY_NAME = 'InternitAtasBundle:Atas';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitAtasBundle:Atas';
	
	protected $defaultRoute = 'atas_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new AtasType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new Atas();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}   
	
	private function setArquivo($form, $obj){
	
		$file = $form['arquivo']['file']->getData();
		 		
		if(!empty($file))
		{
			$arquivo = new AtasArquivo();
			$arquivo->setTitle("ata".date("U").".".$file->guessExtension());
			$arquivo->setLabel("ata");
				
			$upload = new UploadAtasHelper();
			$upload->setMedia($arquivo);
			$upload->setFile($file);
			$upload->doUpload();
			$this->getDoctrine()->getManager()->persist($upload->getMedia());
			$obj->setArquivo($upload->getMedia());
		}
		elseif($obj->getArquivo()->getTitle()==null){
			$obj->setArquivo(null);
		}
	}	
	
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Ata excluída com sucesso!");
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
    	$this->setArquivo($form, $obj);
    	
        //cria
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Ata cadastrada com sucesso!');
        }
        //altera
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Ata alterada com sucesso!');
        }
    }
    
    public function documentoAction($url)
    {
    
    	$response = new Response();
    	if ($response->isNotModified($this->getRequest())) {
    		return $response;
    	}else{
    		$arquivo = $this->getDoctrine()->getRepository('InternitAtasBundle:AtasArquivo')->findOneBy(array('url' => $url));
    		$base64 = $arquivo->getBin()->getValue();
    
    		$stream = fopen("data:".$arquivo->getMimeType().";base64,". $base64 , 'r');
    		$response = new Response(stream_get_contents($stream), 200);
    		$response->headers->set('Content-Type', $arquivo->getMimeType());
    		$response->headers->set('Content-Disposition', 'attachment; filename="'.$arquivo->getTitle().'"');
    		$response->setLastModified($arquivo->getUpdatedAt());
    		return $response;
    	}
    }    
    
}
