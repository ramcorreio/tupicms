<?php
namespace Internit\InteressadoBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;

use Symfony\Component\Routing\RequestContext;
use Internit\InteressadoBundle\Form\InteressadoType;
use Internit\InteressadoBundle\Entity\Interessado;
use Internit\ContactBundle\Entity\ContactPerson;
use Tupi\ContentBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Internit\ContactBundle\Form\UploadHelper;

class InteressadoAdminController extends CrudController
{
    const REPOSITORY_NAME = 'InternitInteressadoBundle:Interessado';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitInteressadoBundle:Interessado';
    
    protected $defaultRoute = 'tupi_interessado_home';
    
    protected function createTypedForm($type)
    {
    	$em = $this->getDoctrine()->getManager();
    	$repository = $em->getRepository('InternitInteressadoBundle:InteressadoSubject');
        return $this->createForm(new InteressadoType($repository, $em), $type);
    }
    
    protected function initObject($id = null) 
    {
        
        $obj = new Interessado();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
    }
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {

    }
}