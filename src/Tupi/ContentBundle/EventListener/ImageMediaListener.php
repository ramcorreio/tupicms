<?php
namespace Tupi\ContentBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\PreRemove;

use Tupi\ContentBundle\Entity\ImageMedia;

/**
 * 
 * Classe criada para notificar a todos módulos envolvidos quando 
 * uma imagem estiver sendo excluída
 * 
 * O objetivo disso é criar um desacoplamento de imagens com os objetos que as utilizam.
 * 
 * @author Rodrigo Macedo
 *
 */
class ImageMediaListener
{
	
	const onRemove = 'onRemove';
	
	/** 
	 * @PreRemove 
	 */
	public function preRemoveHandler(ImageMedia $media, LifecycleEventArgs $event)
	{
		$event->getEntityManager()->getEventManager()->dispatchEvent(self::onRemove, $event);
		
		return true;
	}
}