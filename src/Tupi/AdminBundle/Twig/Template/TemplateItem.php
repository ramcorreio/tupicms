<?php
namespace Tupi\AdminBundle\Twig\Template;

use Serializable;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Tupi\ContentBundle\Twig\Renderer;

class TemplateItem implements Serializable
{
	private $label;
	
	private $value;
	
	public function __construct($label = null, $value = null)
	{
		$this->label = $label;
		$this->value = $value;
	}
	
	public function getLabel()
	{	
		return $this->label;
	}
	
	public function setLabel($label)
	{
		$this->label = $label;
		
		return $this;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function getService()
	{
		return $this->service;
	}
	
	public function render(Controller $controller, Renderer $link) 
	{
		$controller->get('logger')->debug("Processando TemplateItem...");
		$controller->get('logger')->debug($controller->getRequest()->getRequestUri());
		$controller->get('logger')->debug($controller->getRequest()->get('_route'));
		//verifica se é template ou action
		if(!empty($this->value) && stripos($this->value, '.html.twig') === false)
		{
			return $controller->forward($this->value, array('page' => $link));
		}
		else if(!empty($this->value) && stripos($this->value, '.html.twig') > 0)
		{
			return $controller->render($this->value, array('session' => $link));
		}
		else
		{
			throw new NotFoundHttpException("Página não encontrada");
		}
	}
	
	public function serialize() {
		
		return base64_encode(serialize(get_object_vars($this)));
	}
	
	public function unserialize($data) {
		
		$values = @unserialize(base64_decode($data));
		if(!$values)
			throw new ParseException($data);
			
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
	}
}