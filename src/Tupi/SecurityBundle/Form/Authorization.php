<?php
namespace Tupi\SecurityBundle\Form;

class Authorization
{
	public static $AUTHORIZATIONS = array('VIEW' => 'Visualizar', 'CREATE' => 'Incluir', 'EDIT' => 'Atualizar', 'DELETE' => 'Excluir');
	
	private $resource;
	
	private $authorizations;
	
	public function getResource()
	{
		return $this->resource;
	}
	
	public function setResource($resource)
	{
		$this->resource = $resource;
		
		return $this;
	}
	
	public function getAuthorizations()
	{
		return $this->authorizations;
	}
	
	public function setAuthorizations($authorizations)
	{
		$this->authorizations = $authorizations;
	
		return $this;
	}
	
}