<?php
namespace Tupi\AdminBundle\Menu;

use Symfony\Component\EventDispatcher\Event;
use Knp\Menu\ItemInterface;


final class BuildEvent extends Event
{
    /**
     * @var string
     */
    const MENU_BUILDER = 'menu.build.listener';
    
    private $menu;
    
    /**
     * @param \Knp\Menu\ItemInterface $menu
     */
    public function __construct(ItemInterface $menu)
    {
        $this->menu = $menu;
    }
    
    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }
    
}