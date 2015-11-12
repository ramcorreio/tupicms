<?php
namespace Internit\SiteBaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tupi\ContentBundle\Controller\PageController;
use Tupi\ContentBundle\Types\PageStatusType;

class BaseController extends \Tupi\AdminBundle\Controller\BaseController
{	
	/**
	 * Creates, submits and returns a Form instance from the type of the form if it was submitted.
	 * 
	 * @param string|FormTypeInterface $type    The built type of the form
	 * @param mixed                    $data    The initial data for the form
	 * @param array                    $options Options for the form
	 *
	 * @return Form
	 */
	public function createForm($type, $data = null, array $options = array())
	{
	    $form = parent::createForm($type, $data, $options);
	    $form->handleRequest($this->getRequest());
	    
	    return $form;
	}
}