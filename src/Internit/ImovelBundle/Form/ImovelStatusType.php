<?php

namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Internit\ImovelBundle\Controller\ImovelStatusController;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ImovelStatusType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('id', 'hidden')		
		->add('status', 'text', array(
			'label' => 'Nome do Status',
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar um nome para o status.'))
			)
		));
	}
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\ImovelBundle\Entity\ImovelStatus',
        ));
    }
	
	public function getName()
	{
		return 'status';
	}
}
