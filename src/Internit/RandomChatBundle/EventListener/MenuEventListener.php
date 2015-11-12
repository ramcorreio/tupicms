<?php
namespace Internit\RandomChatBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;

class MenuEventListener implements MenuCreator
{
    public function onCreate(BuildEvent $event)
    {   
        $random = $event->getMenu()->addChild('Chats Randômicos', array('route' => 'randomchat_home'));        
    }
}