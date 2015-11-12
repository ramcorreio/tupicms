<?php
namespace Internit\OferecaBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;


class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {        
        $contatos = $event->getMenu()->addChild('Ofereça seu Terreno');
        $contatos->setChildrenAttribute('class', 'submenu');
        
        $contatos->addChild('Grupo de E-mails', array('route' => 'tupi_groupOfereca_home'));
        $contatos->addChild('Assunto', array('route' => 'tupi_subjectOfereca_home'));        
        $contatos->addChild('Contatos', array('route' => 'tupi_ofereca_home'));
        
    }
}