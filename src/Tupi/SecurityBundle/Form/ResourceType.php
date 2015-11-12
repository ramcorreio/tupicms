<?php
namespace Tupi\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class ResourceType extends AbstractType 
{	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
		$builder->add('id', 'hidden')
		->add('name', 'text', array(
			'label' => 'Nome',
			'required' => true,
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar o nome.')),
				new Length(array('max' => 150, 'maxMessage' => 'Limite de caracteres atingido.'))
			)
		))
		->add('path', 'text', array(
			'label' => 'URL',
			'required' => true,
			'constraints' => new NotBlank(array('message' => 'É necessário informar a url.'))
		));
	}
	
	public function getName()
	{
		return 'resource';
	}

}
