<?php

namespace Internit\ImovelBundle\Form;

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
        ->add('position', 'hidden')
        ->add('label', 'text', array(
			'label' => 'Legenda',
			'required' => true
		));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\ImovelBundle\Entity\Image'
        ));
    }
    
    public function getName()
    {
        return 'imovel_image';
    }
}
