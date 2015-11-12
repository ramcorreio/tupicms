<?php
namespace Tupi\SecurityBundle\Form;

use Tupi\SecurityBundle\Entity\User;

class PermissionHelper
{
	private $user;
	
	private $permissoes;
	
	public function getUser() {
		
		return $this->user;
	}
	
	public function setUser(User $user) {
		
		$this->user = $user;
		
		return $this;
	}
	
	public function getPermissoes() {
		
	 	return $this->permissoes;
	}
	
	public function setPermissoes($permissoes) {
	
		$this->permissoes = $permissoes;
	
		return $this;
	}
}