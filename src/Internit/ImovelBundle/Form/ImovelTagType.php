<?php

namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Internit\ImovelBundle\Controller\ImovelTagController;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImovelTagType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('id', 'hidden')		
		->add('tag', 'text', array(
			'label' => 'Nome da Tag',
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar um nome para a tag.')),
				new Length(array(
					'min' => 3, 
					'max' => 100,
					'minMessage' => 'Nome da tag muito curto.',
					'maxMessage' => 'Nome da tag muito longo.'
				))
			)
		));
	}
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\ImovelBundle\Entity\ImovelTag',
        ));
    }
	
	public function getName()
	{
		return 'status';
	}
}
