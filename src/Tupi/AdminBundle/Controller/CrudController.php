<?php
namespace Tupi\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Commom\Paginator;
use Tupi\AdminBundle\Twig\EditableChecker;


abstract class CrudController extends BaseController implements EditableChecker
{
	protected $bundleName;
	
	protected $defaultRoute;
	
	protected $editable;
	
	protected $listaTemplate = 'lista.html.twig';
	
	protected $cadastroTemplate = 'cadastro.html.twig';
	
	protected function doAction($actionName = null, $id = null)
	{
		switch ($actionName)
		{
			case 'edit':
			case 'new':
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
			default: {
				 
				if(!is_null($this->getRequest()->get('lines'))) {
					$this->getRequest()->getSession()->set('lines', $this->getRequest()->get('lines'));
				}
				 
				$builder = $this->listItens();
				$paginator = new Paginator($builder, $fetchJoinCollection = true);
				$paginator->init($this->getRequest()->get('page', 1), $this->getRequest()->getSession()->get('lines', 10));
		
				return $this->render($this->bundleName . ':' . $this->listaTemplate, array("paginator" => $paginator));
			}
		}
	}
	
	public function indexAction($actionName = null, $id = null)
	{
		$this->editable = ($this->getRequest()->get('actionName') == 'edit');
		return $this->doAction($actionName, $id);
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
	
	protected abstract function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em);
	
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
	
	private function executeRemove($wrap, $name = '')
	{
		$em = $this->getManager();
	    $returnVal = $this->createReturnVal();
	    $this->{'remove' . $name}($returnVal, $wrap);
	    $em->flush();
	    
	    $this->get('session')->getFlashBag()->add('notice', $returnVal->getMessage());
	    return $this->redirect($this->generateUrl($returnVal->getRoute()));
	}
	
	private function load($id, $errors = array())
	{
		$obj = $this->initObject($id);
		return $this->loadObject($obj, $errors);
	}
	
	private function loadObject($obj, $errors = array())
	{
		$form = $this->createMyForm($obj);
	
		return $this->render($this->bundleName . ':' . $this->cadastroTemplate, array(
			'title_controle' => ($this->isEdit() ? 'Editar' : 'Novo'),
			'form' => $form->createView(),
			'errors' => $errors
		));
	}
	
	private function executeSave($id = null)
	{
		$obj = $this->initObject($id);
		
		//prepara dados do form
		$form = $this->createMyForm($obj);
		
		if (!$form->isValid()) {
			
			$errors = $this->get('validator')->validate($form);
			return $this->loadObject($obj, $errors);
		}
		else {
			$em = $this->getManager();
			
		    $returnVal = $this->createReturnVal();
			$this->save($returnVal, $id, $obj, $form, $em);
			$em->flush();
			
			$this->get('session')->getFlashBag()->add('notice', $returnVal->getMessage());
			return $this->redirect($this->generateUrl($returnVal->getRoute()));
		}
	}
}