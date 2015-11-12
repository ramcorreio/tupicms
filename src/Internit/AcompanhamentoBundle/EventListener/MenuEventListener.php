<?php
namespace Internit\AcompanhamentoBundle\EventListener;

use Tupi\AdminBundle\Menu\MenuCreator;
use Tupi\AdminBundle\Menu\BuildEvent;


class MenuEventListener implements MenuCreator
{
    
    public function onCreate(BuildEvent $event)
    {
        $acompanhamentos = $event->getMenu()->addChild('Acompanhamento');
        $acompanhamentos->setChildrenAttribute('class', 'submenu');

        $acompanhamentos->addChild('Acompanhamentos', array('route' => 'tupi_acompanhamento_home'));
        $acompanhamentos->addChild('Etapas', array('route' => 'tupi_etapa_home'));
    }
}