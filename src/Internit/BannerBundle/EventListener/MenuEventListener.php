<?php
namespace Internit\BannerBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;


class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {        
        $event->getMenu()->addChild('Banner',array('route' => 'banner_home'));       
    }
}