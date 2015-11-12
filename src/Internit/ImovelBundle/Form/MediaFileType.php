<?php
namespace Internit\ImovelBundle\Form;

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
			'data_class' => 'Internit\ImovelBundle\Entity\Arquivo'
		));
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('id', 'hidden')
		->add('label', 'text', array(
			'label' => false,
			'data' => 'Documento',
			'required' => true,
			'attr' => array('class' => 'hidden')
		));
	}
	
	public function getName()
	{
		return 'imovel_media';
	}
}