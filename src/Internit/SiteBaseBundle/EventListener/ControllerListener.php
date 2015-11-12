<?php
namespace Peper\DefaultBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Tupi\ContentBundle\Controller\PageController;

class ControllerListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
    	
    	if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
    		// return immediately
    		return;
    	}
    	
    	$controller = $event->getController();
    	/*
    	 * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
    	 * If it is a class, it comes in array format
    	 */
    	if (!is_array($controller)) {
    		return;
    	}
    	
    	$rObj = new \ReflectionObject($controller[0]);
    	if(!$rObj->hasMethod("getDoctrine")) {
    		return;
    	}
    	
    	$menus = $controller[0]->getDoctrine()->getRepository(PageController::REPOSITORY_NAME)->listMenuPages();
    	$endereco = $controller[0]->getDoctrine()->getRepository(PageController::REPOSITORY_NAME)->getPageFromUrl('endereco');
    	
    	$event->getRequest()->request->set("menus", $menus);
    	$event->getRequest()->request->set("endereco", $endereco->getChildren());
    	
    }
}