<?php
namespace Tupi\AdminBundle\Menu;

/**
 * 
 *
 */
interface MenuCreator
{
    public function onCreate(BuildEvent $event);
}