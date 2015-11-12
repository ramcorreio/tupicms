<?php

namespace Internit\OferecaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tupi\ContentBundle\Form\MediaFileType;

class ArquivoType extends AbstractType
{		
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('media', new MediaFileType(), array(
        	    'label' => false,
        		'attr' => array('class' => 'hidden')
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\OferecaBundle\Entity\Arquivo'
        ));
    }
    
    public function getName()
    {
        return 'ofereca_arquivo';
    }
}
