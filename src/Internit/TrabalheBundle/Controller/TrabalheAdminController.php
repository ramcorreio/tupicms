<?php
namespace Internit\TrabalheBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;

use Symfony\Component\Routing\RequestContext;
use Internit\TrabalheBundle\Form\TrabalheType;
use Internit\TrabalheBundle\Entity\Trabalhe;
use Internit\ContactBundle\Entity\ContactPerson;
use Tupi\ContentBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Internit\ContactBundle\Form\UploadHelper;

class TrabalheAdminController extends CrudController
{
    const REPOSITORY_NAME = 'InternitTrabalheBundle:Trabalhe';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitTrabalheBundle:Trabalhe';
    
    protected $defaultRoute = 'tupi_trabalhe_home';
    
    protected function createTypedForm($type)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('InternitTrabalheBundle:TrabalheSubject');
        return $this->createForm(new TrabalheType($repository, $em), $type);
    }
    
    protected function initObject($id = null) 
    {
        
        $contactRequest = new Trabalhe();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
    }
    
protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
    	
    	if(!is_null($obj->getCurriculo()))
    	{
    		if(is_null($obj->getCurriculo()->getBin()->getId()))
    		{
    			$file = $form['curriculo']['file']->getData();
    			$arquivo = $obj->getCurriculo();
    			$arquivo->setTitle("documento_".date("U").".".$file->guessExtension());
    			$arquivo->setLabel("documento");
    	
    			$upload = new UploadHelper($this->container);
    			$upload->setMedia($arquivo);
    			$upload->setFile($file);
    			$upload->doUpload();
    			$file = $upload->getMedia();
    			$em->persist($file);
    			$obj->setCurriculo($file);
    		}
    	}
    	
        $obj->setCreatedAt(new \DateTime());
        $em->merge($obj);
    }
    
    public function documentoAction($url)
    {
    
    	$response = new Response();
    	if ($response->isNotModified($this->getRequest())) {
    		return $response;
    	}else{
    		$arquivo = $this->getDoctrine()->getRepository('InternitContactBundle:Arquivo')->findOneBy(array('url' => $url));
    		$base64 = $arquivo->getBin()->getValue();
    
    		$response = new Response(base64_decode($base64), 200);
    		$response->headers->set('Content-Type', $arquivo->getMimeType());
    		$response->setLastModified($arquivo->getUpdatedAt());
    		return $response;
    	}
    }
    
}