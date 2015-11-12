<?php
namespace Tupi\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaFileType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Tupi\ContentBundle\Entity\ImageMedia'
		));
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('id', 'hidden')
		->add('title', 'text', array(
			'label' => 'Título da mídia',
			'required' => true,
			'constraints' => new NotBlank(array('message' => 'É necessário informar o título'))
		))
		->add('summary', 'text', array(
			'label' => 'Texto alternativo (Se a imagem não carregar)',
			'required' => true,
			'constraints' => new NotBlank(array('message' => 'É necessário informar o texto alternativo'))
		))
		->add('label', 'text', array(
			'label' => 'Legenda da mídia',
			'required' => true,
			'constraints' => new NotBlank(array('message' => 'É necessário informar a legenda'))
		))
		->add('source', 'text', array(
			'label' => 'Fonte',
			'required' => false
		));
	}
	
	public function getName()
	{
		return 'imagemedia';
	}
}