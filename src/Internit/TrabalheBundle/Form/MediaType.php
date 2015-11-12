<?php
namespace Internit\TrabalheBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    
    private $options;
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Internit\ContactBundle\Entity\Arquivo'
		));
	}
	
	public function __construct()
	{
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	    $required = false;
		$constraints = array();

		array_push($constraints, 
			new File(array(
				'maxSize' => '3M',
				'maxSizeMessage' => 'Arquivo muito grande ({{ size }} {{ suffix }}). Tamanho máximo de {{ limit }} {{ suffix }}.',
				'mimeTypes' => array("image/jpeg", "image/gif", "image/png", "application/msword", "application/pdf", "application/x-pdf", "application/vnd.ms-office", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"),
				'mimeTypesMessage' => 'Arquivo inválido ({{ type }}). Os tipos permitidos são {{ types }}.'
			))
		);
				
		$builder->add('id', 'hidden')
		->add('file', 'file', array(
			'label' => 'Selecionar mídia',
			'required' => $required,
			'constraints' => $constraints,
			'mapped' => false
		));
	}
	
	public function getName()
	{
		return 'acompanhante_media';
	}
}