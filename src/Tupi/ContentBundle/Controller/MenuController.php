<?php
namespace Tupi\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\ContentBundle\Entity\NodeMenu;
use Tupi\ContentBundle\Entity\Menu;
use Tupi\ContentBundle\Form\MenuType;
use Tupi\AdminBundle\Commom\Util;
use Tupi\AdminBundle\Twig\Template\ChangePageEvent;

class MenuController extends BaseController
{
    const REPOSITORY_NAME = 'TupiContentBundle:Menu';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected function createTypedForm($type)
    {
        $changeEvent = new ChangePageEvent();
        $this->get('event_dispatcher')->dispatch(ChangePageEvent::CHANGE_PAGE, $changeEvent);
    
        return $this->createForm(new MenuType($changeEvent), $type);
    }
    
	public function indexAction()
	{
	    $form = $this->createTypedForm($this->initObject());
	    return $this->render('TupiContentBundle:Menu:index.html.twig', array("form" => $form->createView()));
	}
	
	public function formAction($id)
	{
		
		$this->getLogger()->debug("Carregando dados...");
		$menu = $this->initObject($id);
 		$form = $this->createTypedForm($menu);
		$this->getLogger()->debug($form->getData());
		$this->getLogger()->debug($menu);
		if($this->getRequest()->isMethod('POST'))
		{
			$this->getLogger()->debug("Salvando dados...");
			$form->handleRequest($this->getRequest());
			
			if($form->isValid()) {				
				$message = ''; 
				if(is_null($menu->getId())) {
					$this->getLogger()->debug($this->getRepository()->getNextPosition($menu->getParent()));
					$menu->setCreatedAt(new \DateTime());
					$menu->setUpdatedAt(new \DateTime());
					$menu->setPosition($this->getRepository()->getNextPosition($menu->getParent()));
					$this->getRepository()->persist($menu);
					$message = 'Menu cadastrado com sucesso!';
				} else {
					$menu->setUpdatedAt(new \DateTime());
					$this->getRepository()->update($menu);
					$message = 'Menu alterado com sucesso!';
				}
				
				
				$this->getRepository()->flush($menu);
				$this->get('session')->getFlashBag()->add('notice', $message);
				$form = $this->createTypedForm($menu);
			}
			
			$this->getLogger()->debug($menu);
		}
		
		return $this->render('TupiContentBundle:Menu:form.html.twig', array("form" => $form->createView()));
	}
	
	public function redirectAction()
	{
		$this->getLogger()->debug("Carregando dados...");
		$form = $this->createFormBuilder()
		->add('menuRedirect', 'entity', array(
				'label' => 'Menus',
				'class' => 'TupiContentBundle:Menu',
				'property' => 'title',
				'required'    => true,
				'empty_value' => 'Escolha o menu',
				'data' => 1
		))
		->getForm();
			
		return $this->render('TupiContentBundle:Menu:form_redirect.html.twig', array("form" => $form->createView()));
	}
	
	public function moveAction(Request $request)
	{
		$this->getLogger()->info('moving action....');
		$node = $request->get('node', null);
		$to = $request->get('to', null);
		
		//convertendo a entrada em objeto
		$menuNode = new NodeMenu();
		$menuNode = $menuNode->unserialize($node);
		$this->getLogger()->info('node: ' . $menuNode);
		
		$toNode = new NodeMenu();
		$toNode = $toNode->unserialize($to);
		$this->getLogger()->info('to: ' . $toNode);
		
		$parent = $this->getRepository()->findOneBy(array('id'=> $toNode->getId()));
		foreach ($toNode->getChildren() as $childPos => $childKey) 
		{
			$child = $this->getRepository()->findOneBy(array('id'=> $childKey));
			$child->setParent($parent);
			$child->setPosition($childPos);
			$this->getRepository()->update($child);
		}
		
		$this->getRepository()->flush();
		$toJson = array($this->createMenuNode($parent));
		
		return $this->toJsonArray($toJson);
	}
	
	public function childrenAction(Request $request)
	{
		$this->getLogger()->info('Recuperando nó....');
		$requestNode = $request->get('node', null);
		$this->getLogger()->info('Node info: ', $requestNode);

		//convertendo a entrada em objeto
		$inputNode = new NodeMenu();
		$inputNode = $inputNode->unserialize($requestNode);
		$this->getLogger()->info($inputNode);
		
		if($inputNode->getId() == '#') 
		{
			$node = $this->getRepository()->getRoot();
			$toJson = array($this->createMenuNode($node));
		}
		else 
		{
			$node = $this->getRepository()->findOneBy(array('id' => $inputNode->getId()));
			$toJson = $this->mountTree($node)->getChildren();
		}
		
		return $this->toJsonArray($toJson);
	}
	
	public function deleteAction(Request $request)
	{
		$this->getLogger()->debug("Carregando dados to delete...");
		//recupera o array do request
		$input = $request->get('menu');
		$menu = $this->getRepository()->findOneBy(array('id' => $input['id']));
		var_dump($menu);
		$this->getRepository()->remove($menu);
		$toJson = array($this->createMenuNode($menu));
		$this->getRepository()->flush();
		
		return $this->toJsonArray($toJson);
	}
	
	private function createMenuNode(Menu $node)
	{	
		$jsNode = new NodeMenu();
		$jsNode->setId($node->getId());
		$jsNode->setParent(is_null($node->getParent()) ? '#' : $node->getParent()->getId());
		$jsNode->setText($node->getTitle());
		$jsNode->setPosition($node->getPosition());
		if(count($node->getChildren()) > 0) {
			$jsNode->setChildren(true);
		}
		return $jsNode; 
	}
	
	private function toJsonArray(array $toJson) {
		
		$content = '[';
		foreach ($toJson as $child) 
		{
			$content .= $child->serialize() . ',';
		}

		$content = Util::removeLastComma($content) . ']';
		
		$this->getLogger()->info('Recuperando nó....');
		$this->getLogger()->info($content);
		$response = new Response($content);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
	
	private function mountTree(Menu $node)
	{
		$toJson = $this->createMenuNode($node);
		$toJson->setChildren(array());
		foreach ($node->getChildren() as $child)
	    {
	    	$jsChild = $this->createMenuNode($child);
	    	$toJson->addChild($jsChild);
	    }
	    
	    return $toJson;
	}
	
	private function initObject($id = null)
	{
	    $menu = new Menu();
	    if(!empty($id)) {
	        $menu = $this->getRepository()->findOneBy(array('id' => $id));
	    }
	    return $menu;
	}
}
