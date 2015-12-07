<?php
namespace Internit\AtasBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;

class MenuEventListener implements MenuCreator
{
    public function onCreate(BuildEvent $atas)
    {       	
    	$atas->getMenu()->addChild('Atas', array('route' => 'atas_home'));
    }
}