<?php
namespace Internit\OferecaBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;

use Symfony\Component\Routing\RequestContext;
use Internit\OferecaBundle\Form\OferecaType;
use Internit\OferecaBundle\Entity\Ofereca;
use Internit\ContactBundle\Entity\ContactPerson;
use Tupi\ContentBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Internit\ContactBundle\Form\UploadHelper;

class OferecaAdminController extends CrudController
{
    const REPOSITORY_NAME = 'InternitOferecaBundle:Ofereca';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitOferecaBundle:Ofereca';
    
    protected $defaultRoute = 'tupi_ofereca_home';
    
    protected function createTypedForm($type)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('InternitOferecaBundle:OferecaSubject');
        return $this->createForm(new OferecaType($repository,$em), $type);
    }
    
    protected function initObject($id = null) 
    {
        
        $contactRequest = new Ofereca();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
    }
    
protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
    	
        $obj->setCreatedAt(new \DateTime());
        $em->merge($obj);
    }
    
    public function documentoAction($url)
    {
    	$response = new Response();
    	if ($response->isNotModified($this->getRequest())) {
    		return $response;
    	}else{
    		$arquivo = $this->getDoctrine()->getRepository('TupiContentBundle:ImageMedia')->findOneBy(array('url' => $url));
            
    		$base64 = $arquivo->getBin()->getValue();
    
    		$response = new Response(base64_decode($base64), 200);
    		$response->headers->set('Content-Type', $arquivo->getMimeType());
    		$response->setLastModified($arquivo->getUpdatedAt());
    		return $response;
    	}
    }
    
}