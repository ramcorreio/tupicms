<?php
namespace Tupi\AdminBundle\Twig\Template;

interface ChangePageInterface
{
	public function onChange(ChangePageEvent $event);
}