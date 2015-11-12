<?php
namespace Tupi\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpKernel\Exception\FatalErrorException;

abstract class BaseController extends Controller
{
	protected $repositoryName; 
	
	/**
	 * Função para recuperar o serviço de log 
	 */
	protected function getLogger()
	{
		return $this->get('logger');
	}
	
	protected function getRepository()
	{
		return $this->getRespositoryName($this->repositoryName);
	}
	
	protected function getRespositoryName($repositoryName) {
		
		return $this->getDoctrine()->getRepository($repositoryName);
	}
}