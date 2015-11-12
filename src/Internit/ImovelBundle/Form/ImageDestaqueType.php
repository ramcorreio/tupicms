<?php
namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageDestaqueType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Internit\ImovelBundle\Entity\ImovelImageMedia'
		));
	}
	
	public function __construct($data,$tipo)
	{
	    $this->data = $data;
	    $this->tipo = $tipo;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$required = false;
		$constraints = array();
	
		if(is_null($this->data))
		{
			$required = true;
			array_push($constraints, new NotNull(array('message' => 'É necessário informar a Imagem de '.$this->tipo)));
		}
		
		array_push($constraints, 
			new File(array(
				'maxSize' => '3M',
				'maxSizeMessage' => 'Arquivo muito grande ({{ size }} {{ suffix }}). Tamanho máximo de {{ limit }} {{ suffix }}.',
				'mimeTypes' => array("image/jpeg", "image/gif", "image/png"),
				'mimeTypesMessage' => 'Arquivo inválido ({{ type }}). Os tipos permitidos são {{ types }}.'
			))
		);
				
		$builder->add('id', 'hidden')
		->add('imagem', 'file', array(
			'label' => false,
			'required' => $required,
			'constraints' => $constraints,	
			'mapped' => false
		));
	}
	
	public function getName()
	{
		return 'imovel_image_destaque';
	}
}