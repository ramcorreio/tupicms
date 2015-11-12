<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Commom\Paginator;
use Tupi\AdminBundle\Twig\EditableChecker;
use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class CrudCustomController extends BaseController implements EditableChecker
{
	protected $bundleName;
	
	protected $defaultRoute;
	
	protected $editable;
	
	protected $listaTemplate = 'lista.html.twig';
	
	protected $cadastroTemplate = 'cadastro.html.twig';
	
	protected $filter;
	
	protected function doAction($actionName = null, $id = null)
	{
		switch ($actionName)
		{
		    case 'new':
			case 'edit':
				if($this->getRequest()->isMethod('POST'))
					return $this->executeSave($id);
				else
					return $this->load($id);
				break;
			case 'remove':
			    if (strcasecmp($id, 'all') == 0)
			        return $this->executeRemove($id, 'all');
			    else
			        return $this->executeRemoveSingle($id);
		}
	}
	
	public function indexAction($actionName = null, $id = null, $filter = null)
	{
		$this->editable = ($this->getRequest()->get('actionName') == 'edit');
		$this->filter = $filter;
		return $this->doAction($actionName, $id);
	}
	
	private function getList()
	{
	    return $this->getRepository()->listForm($this->filter);
	}
	
	protected function renderTemplate($form)
	{
        return $this->render($this->bundleName . ':' . $this->cadastroTemplate, array(
            "list" => $this->getList(),
            "form" => $form->createView(),
            "filter" => $this->filter
        ));
	}
	
	/**
	 * Utilizado para delegar a classe que a obrigação de criar seu AbstractType
	 */
	protected function createTypedForm($type)
	{
	}
	
	public function createMyForm($type)
	{
		$form = $this->createTypedForm($type);
	
		if(null === $form) {
			throw new \LogicException('O form está null na função "protected function createTypedForm($type)".');
		}
	
		try {
			$form->handleRequest($this->getRequest());
		}
		catch (ContextErrorException $e) {
			$this->getLogger()->error('Erro ao processar formulário.', $e->getContext());
		}
	
		return $form;
	}
	
	public function isEdit() {
		
		return $this->editable;
	}
	
	private function createReturnVal() 
	{
	    $returnVal = new ReturnVal();
	    $returnVal->setRoute($this->defaultRoute);
	    
	    return $returnVal;
	}
	
	private function getManager() 
	{
	    return $this->getDoctrine()->getManager();
	}
	
	protected abstract function initObject($id);
	
	protected abstract function save(ReturnJson $return, $id = null, $obj, $form, EntityManager $em);
	
	protected function listItens($key = 'item') {
		
		return $this->getRepository()->listItens($key);
	}
	
	protected function removeall(ReturnVal $return, $obj)
	{
	    $values = $this->getRequest()->get('idval');
	    
	    $rs = $this->getRepository()->removeAll("item", $values);
	    $return->setMessage("Todos itens excluídos com sucesso!");
	    return $rs;
	}
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Item excluída com sucesso!");
	}
	
	private function remove(ReturnVal $return, $obj)
	{
		$this->getRepository()->remove($obj);
		$this->removed($return);
	}
	
	private function executeRemoveSingle($id)
	{
	    $obj = $this->initObject($id);
	    return $this->executeRemove($obj);
	}
	
	private function executeRemove($obj, $name = '')
	{
		$em = $this->getManager();
		$em->remove($obj);
	    $em->flush();
	    
	    return $this->redirect($this->generateUrl($this->defaultRoute, array("filter" => $this->filter)));
	}
	
	private function load($id, $errors = array())
	{
		$obj = $this->initObject($id);
		return $this->loadObject($obj, $errors);
	}
	
	private function loadObject($obj, $errors = array())
	{
		$form = $this->createMyForm($obj);
		return $this->renderTemplate($form);
	}
	
	private function executeSave($id = null)
	{
		$obj = $this->initObject($id);
		$returnJson = new ReturnJson();
		$form = $this->createMyForm($obj);
		if (!$form->isValid()) {
		    
		    $returnJson->setErro(true);
			$returnJson->setMessage($form->getErrorsAsString());
			
			return new JsonResponse($returnJson->getJson());
		}
		else {
		    
			$em = $this->getManager();
			$this->save($returnJson, $id, $obj, $form, $em);
			$em->flush();
			$returnJson->setErro(false);
			
			return new JsonResponse($returnJson->getJson());
		}
	}
}