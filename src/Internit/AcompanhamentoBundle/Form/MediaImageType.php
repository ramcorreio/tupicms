<?php
namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaImageType extends AbstractType
{

private $data;
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
		     'data_class' => 'Internit\AcompanhamentoBundle\Entity\AcompanhamentoImageMedia'
		));
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('id', 'hidden')
		->add('imagem', 'file', array(
			'label' => false,
			'required' => false,
			'constraints' => new File(array(
				'maxSize' => '3M',
				'maxSizeMessage' => 'Arquivo muito grande ({{ size }} {{ suffix }}). Tamanho máximo de {{ limit }} {{ suffix }}.',
				'mimeTypes' => array("image/jpeg", "image/gif", "image/png"),
				'mimeTypesMessage' => 'Arquivo inválido ({{ type }}). Os tipos permitidos são {{ types }}.'
			)),	
			'mapped' => false
		));
	}
	
	public function getName()
	{
		return 'acompanhamento_image';
	}
}