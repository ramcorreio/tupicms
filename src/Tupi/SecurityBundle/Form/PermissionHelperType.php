<?php
namespace Tupi\SecurityBundle\Form;

use Tupi\SecurityBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tupi\AdminBundle\Twig\EditableChecker;

class PermissionHelperType extends AbstractType
{
	private $checker;
	
	private $repository;
	
	public function __construct(EditableChecker $checker, $repository) {
		$this->checker = $checker;
		$this->repository = $repository;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options) 
	{
		$builder->add('user', new UserType($this->checker, $this->repository));
		
		$builder->add('permissoes', 'collection', array(
			'type' => new PermissionCollectionType(),
			'allow_add'  => true,
			'allow_delete' => true
		));
	}
	
	public function getName()
	{
		return 'permission';
	}
}