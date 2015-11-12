<?php
namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlocoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('bloco', 'text', array(
            'label' => 'Bloco',
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário informar o nome do bloco.'))
            )
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\AcompanhamentoBundle\Entity\Bloco'
        ));
    }
    
    public function getName()
    {
        return 'bloco';
    }
}