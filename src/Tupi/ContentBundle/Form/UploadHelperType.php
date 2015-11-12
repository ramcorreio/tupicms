<?php
namespace Tupi\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;

class UploadHelperType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$required = false;
		$constraints = array();
		
		//verifica se é alteração ou inclusão 
		if(is_null($options['data']->getMedia()->getId())) 
		{
			$required = true;
			array_push($constraints, new NotNull(array('message' => 'É necessário informar o arquivo')));
		}
		
		array_push($constraints, 
			new File(array(
				'maxSize' => '3M',
				'maxSizeMessage' => 'Arquivo muito grande ({{ size }} {{ suffix }}). Tamanho máximo de {{ limit }} {{ suffix }}.',
				'mimeTypes' => array("image/jpeg", "image/gif", "image/png"/* , "image/tiff" */),
				'mimeTypesMessage' => 'Arquivo inválido ({{ type }}). Os tipos permitidos são {{ types }}.'
			))
		);
				
		$builder->add('media', new MediaFileType())
		->add('file', 'file', array(
			'label' => 'Selecionar mídia',
			'required' => $required,
			'constraints' => $constraints
		));
	}
	
	public function getName()
	{
		return 'uploadhelper';
	}
}