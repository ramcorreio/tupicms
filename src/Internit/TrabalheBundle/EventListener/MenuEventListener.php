<?php
namespace Internit\TrabalheBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;


class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {        
        $contatos = $event->getMenu()->addChild('Trabalhe Conosco');
        $contatos->setChildrenAttribute('class', 'submenu');
        
        $contatos->addChild('Grupo de E-mails', array('route' => 'tupi_groupTrabalhe_home'));
        $contatos->addChild('Assunto', array('route' => 'tupi_subjectTrabalhe_home'));        
        $contatos->addChild('Contatos', array('route' => 'tupi_trabalhe_home'));
        
    }
}