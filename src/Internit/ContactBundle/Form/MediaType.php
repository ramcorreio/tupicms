<?php
namespace Internit\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    
    private $options;
    
    private $required;
    
    private $mimeType;
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Internit\ContactBundle\Entity\Arquivo'
		));
	}
	
	public function __construct($required = false, $mimeType = array("image/jpeg", "image/gif", "image/png", "application/msword", "application/pdf", "application/x-pdf", "application/vnd.ms-office", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint"))
	{
	    $this->required = $required;
	    $this->mimeType = $mimeType;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$constraints = array();

		array_push($constraints, 
			new File(array(
				'maxSize' => '3M',
				'maxSizeMessage' => 'Arquivo muito grande ({{ size }} {{ suffix }}). Tamanho máximo de {{ limit }} {{ suffix }}.',
				'mimeTypes' => $this->mimeType,
				'mimeTypesMessage' => 'Arquivo inválido. Os tipos permitidos são: .doc, .docx, .rtf ou .pdf.'
			))
		);
				
		$builder->add('id', 'hidden')
		->add('file', 'file', array(
			'label' => 'Selecionar mídia',
			'required' => $this->required,
			'constraints' => $constraints,
			'mapped' => false
		));
	}
	
	public function getName()
	{
		return 'acompanhante_media';
	}
}