<?php
namespace Internit\InteressadoBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;


class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {        
        $contatos = $event->getMenu()->addChild('Interessado');
        $contatos->setChildrenAttribute('class', 'submenu');
        
        $contatos->addChild('Grupo de E-mails', array('route' => 'tupi_groupInteressado_home'));
        $contatos->addChild('Assunto', array('route' => 'tupi_subjectInteressado_home'));        
        $contatos->addChild('Contatos', array('route' => 'tupi_interessado_home'));
        
    }
}