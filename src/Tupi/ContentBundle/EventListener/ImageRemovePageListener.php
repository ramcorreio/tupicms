<?php
namespace Tupi\ContentBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;

use Tupi\ContentBundle\Controller\PageController;

class ImageRemovePageListener
{
	public function onRemove(LifecycleEventArgs $event)
	{
		$pageRepo = $event->getEntityManager()->getRepository(PageController::REPOSITORY_NAME);
		$pageRepo->removePageUseImage($event->getEntity());
		
		return true;
	}
}