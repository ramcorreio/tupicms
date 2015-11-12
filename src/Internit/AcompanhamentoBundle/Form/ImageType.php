<?php

namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tupi\ContentBundle\Form\MediaFileType;

class ImageType extends AbstractType
{		
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('restrito', 'checkbox', array(
				'label'    => false,
				'required' => false,
		))
        ->add('imageMedia', new MediaFileType(), array(
        	    'label' => false,
        		'attr' => array('class' => 'hidden')
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\AcompanhamentoBundle\Entity\Image'
        ));
    }
    
    public function getName()
    {
        return 'galeria_image';
    }
}
