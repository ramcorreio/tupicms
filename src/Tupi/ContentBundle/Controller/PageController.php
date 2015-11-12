<?php
namespace Tupi\ContentBundle\Controller;

use Tupi\ContentBundle\Form\PageType;
use Tupi\AdminBundle\Controller\BaseController;
use Tupi\ContentBundle\Entity\Page;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;
use Tupi\AdminBundle\Twig\Template\ChangePageEvent;

class PageController extends CrudController
{
	const REPOSITORY_NAME = 'TupiContentBundle:Page';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'TupiContentBundle:Page';
	
	protected $defaultRoute = 'tupi_page_home';
	
	protected function createTypedForm($type)
	{
		//Criado para usar o base controler com formulários customizados.
		$changeEvent = new ChangePageEvent();
		$this->get('event_dispatcher')->dispatch(ChangePageEvent::CHANGE_PAGE, $changeEvent);
		
		return $this->createForm(new PageType($this->getDoctrine()->getManager(), $changeEvent), $type);
	}
	
	protected function initObject($id = null) {
	
		$obj = new Page();
		if(!empty($id)) {
		
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		
		return $obj;
	}
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Página excluída com sucesso!");
	}
	
	protected function save(ReturnVal $return, $id = null, $page, $form, EntityManager $em)
	{
		//criar um nova página
		if(is_null($page->getId())) {
			$page->setCreatedAt(new \DateTime());
			$page->setUpdatedAt(new \DateTime());
			$em->persist($page);
			$return->setMessage('Página cadastrada com sucesso!');
		}
		//alterar
		else {
			$page->setUpdatedAt(new \DateTime());
			$em->merge($page);
			$return->setMessage('Página alterada com sucesso!');
		}
	}
}