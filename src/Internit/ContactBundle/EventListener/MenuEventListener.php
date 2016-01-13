<?php
namespace Internit\ContactBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;


class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {        
        $contatos = $event->getMenu()->addChild('Contatos');
        $contatos->setChildrenAttribute('class', 'submenu');

        $contatos->addChild('Configurações', array('route' => 'tupi_contact_config'));
        $contatos->addChild('Grupo de E-mails', array('route' => 'tupi_group_home'));
        $contatos->addChild('Assunto', array('route' => 'tupi_subject_home'));
        $contatos->addChild('Contatos', array('route' => 'tupi_contact_home'));
        $contatos->addChild('Cadastros', array('route' => 'tupi_person_home'));
    }
}