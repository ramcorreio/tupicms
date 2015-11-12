<?php
namespace Tupi\AdminBundle\Twig\Template;

use ArrayIterator;
use Symfony\Component\EventDispatcher\Event;

class ChangePageEvent extends Event
{

	/**
	 * @var string
	 */
	const CHANGE_PAGE = 'change.page.listener';
	
	
	private $templates;
	
	public function __construct()
	{
		$this->templates = new ArrayIterator(array());
	}
	
	public function addTemplate(TemplateItem $item)
	{
		$this->templates->append($item);
		
		return $this;
	}
	
	public function getTemplates()
	{
		return $this->templates->getArrayCopy();
	}
	
}