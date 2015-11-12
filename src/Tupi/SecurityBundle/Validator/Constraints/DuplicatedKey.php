<?php
namespace Tupi\SecurityBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;


/**
 * Esta classe é parta do SecurityBunble
 * 
 * @author Rodrigo Macedo <rodrigo.macedo@internit.com.br>
 * 
 * Esta classe foi criada para validar chaves duplicadas no banco de dados.
 * 
 */
class DuplicatedKey extends Constraint {
	
	public $message = 'O {{valor}} já está em uso.';
	public $repository;
	
	public function __construct($options = null)
	{
		parent::__construct($options);
		
		if (null === $this->repository) {
			throw new MissingOptionsException(sprintf('A opção "repository" deve ser informada para a constraint %s', __CLASS__), array('repository'));
		}
	}
}