<?php
namespace Internit\ImovelBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;

class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {   
        $news = $event->getMenu()->addChild('Imóvel'); 
    	$news->setChildrenAttribute('class', 'submenu');
    	$news->addChild('Imóveis', array('route' => 'imovel_home'));
    	$news->addChild('Tags', array('route' => 'imovel_tag_home'));
    	$news->addChild('Status', array('route' => 'imovel_status_home'));
    	$news->addChild('Realizadores', array('route' => 'imovel_makers_home'));
    }
}