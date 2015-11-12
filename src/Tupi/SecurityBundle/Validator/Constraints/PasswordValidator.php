<?php

namespace Tupi\SecurityBundle\Validator\Constraints;

class PasswordValidator 
{	
	const STRONG_PASSWORD = '/^.*(?=.{6,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/';
	
	const PASSWORD_NO_WHITE_SPACE = '/[^\s]{5,}/';
	
	public static function password($password) 
	{
		
		if (!preg_match(self::PASSWORD_NO_WHITE_SPACE, $password)) 
		{
			throw new \InvalidArgumentException('Senha fraca.');
		}
		
		return $password;
	}
	
	
}