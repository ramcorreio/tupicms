<?php
namespace Tupi\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tupi\AdminBundle\Controller\BaseController;

use Tupi\ContentBundle\Controller\PageController;
use Tupi\ContentBundle\Controller\MenuController;
use Tupi\ContentBundle\Entity\Page;
use Tupi\ContentBundle\Entity\Menu;
use Tupi\ContentBundle\Entity\LinkContent;
use Tupi\ContentBundle\Types\PageStatusType;
use Tupi\ContentBundle\Twig\Util;
use Tupi\ContentBundle\Twig\Renderer;

use Tupi\AdminBundle\Twig\Template\TemplateItem;
use Tupi\AdminBundle\Twig\Template\ParseException;


/**
 * Controlador padrão
 * 
 */
class DefaultController extends BaseController
{
	public function indexAction()
	{
		$home = $this->getDoctrine()->getRepository(MenuController::REPOSITORY_NAME)->getRoot();
		return $this->renderTemplate($home);
	}
	
	public function pagesAction($path, $ext) 
	{	
		try 
		{
			$paths = explode('/', $path);
			$page = $this->getDoctrine()->getRepository(PageController::REPOSITORY_NAME)->getPageFromUrl(end($paths));
			if($page->getStatus() == PageStatusType::PUBLISHED)
			{
				return $this->renderTemplate($page);
			}
			else
			{
				throw new NotFoundHttpException("Página não encontrada");
			}
		}
		catch (ResourceNotFoundException $e)
		{
			throw new NotFoundHttpException("Página não encontrada");
		}
	}
	
	public function menusAction($path) {
		
		try
		{
			$paths = explode('/', $path);
			foreach ($paths as $itemPath) 
			{
				$menu = $this->getDoctrine()->getRepository(MenuController::REPOSITORY_NAME)->getMenuFromUrl($itemPath);
			}
			
			if($menu->isRedirect()) 
			{
				return $this->redirect(str_replace($menu->getUrl(), $menu->getMenuRedirect()->getUrl(), $path), 301);
			}
			else 
			{
				return $this->renderTemplate($menu);
			}
		}
		catch (ResourceNotFoundException $e)
		{
			throw new NotFoundHttpException("Página não encontrada");
		}
	}
	
	public function renderAction(Renderer $link) {
		
		return $this->renderTemplate($link);
	}
	
	private function renderTemplate(Renderer $link)
	{
		$template = Util::buildTemplate($link);
	    $this->getLogger()->debug("Processando página: " . print_r($template, true));
	    return $template->render($this, $link);
	}
    
    public function adminAction()
    {
    	return $this->render('TupiAdminBundle:Admin:index.html.twig');
    }
}
