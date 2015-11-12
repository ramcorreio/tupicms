<?php

namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

use Internit\ImovelBundle\Controller\ImovelTagController;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tupi\ContentBundle\Form\UploadHelperType;

class ImovelMakersType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('id', 'hidden')		
		->add('name', 'text', array(
			'label' => 'Realizador',
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar um nome para o realizador.')),
				new Length(array(
					'min' => 2, 
					'max' => 100,
					'minMessage' => 'Nome muito curto.',
					'maxMessage' => 'Nome muito longo.'
				))
			)
		))
		->add('url', 'text', array(
            'label' => 'Link do realizador',
            'required' => false,
            'constraints' => array(
                new Url(array('message' => 'O campo {{ value }} precisa ser uma URL válida.'))
            )
        ))
		->add('logo', new ImageDestaqueType($options['data']->getLogo(),'Logo'),array(
		    'required' => true));
	}
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\ImovelBundle\Entity\ImovelMakers',
        ));
    }
	
	public function getName()
	{
		return 'makers';
	}
}
