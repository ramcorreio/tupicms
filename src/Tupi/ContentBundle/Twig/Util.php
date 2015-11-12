<?php
namespace Tupi\ContentBundle\Twig;

use Tupi\AdminBundle\Twig\Template\TemplateItem;
use Tupi\AdminBundle\Twig\Template\ParseException;

class Util 
{
	private function __construct()
	{
	}
	
	public static function buildTemplate(Renderer $renderer)
	{
		$template = new TemplateItem();
		try {
			$template->unserialize($renderer->getTemplateName());
		}
		catch (ParseException $e) {
		}
		 
		return $template;
	}
}