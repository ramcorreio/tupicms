<?php
namespace Tupi\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RecoveryPass extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', 'email', array(
			'label' => 'Email',
			'required' => true,
			'attr' => array('placeholder' => "E-mail cadastrado"),			
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar um email.')),
				new Email(array('message' => 'É necessário informar um email válido.'))
			)
		));

	}
	
	public function getName()
	{
		return 'recovery_pass';
	}
	
}