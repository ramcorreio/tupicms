<?php
namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Internit\ImovelBundle\Entity\ImovelArquivo'
		));
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$constraints = array();
		
		array_push($constraints, 
			new File(array(
				'maxSize' => '3M',
				'maxSizeMessage' => 'Arquivo muito grande ({{ size }} {{ suffix }}). Tamanho máximo de {{ limit }} {{ suffix }}.',
				'mimeTypes' => array("application/msword", "application/pdf", "application/x-pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/zip"),
				'mimeTypesMessage' => 'Arquivo inválido ({{ type }}). Os tipos permitidos são {{ types }}.'
			))
		);
				
		$builder->add('id', 'hidden')
		->add('file', 'file', array(
			'label' => 'Inserir Manual do Usuário',
			'required' => false,
			'constraints' => $constraints,
			'mapped' => false
		));
	}
	
	public function getName()
	{
		return 'imovel_media';
	}
}