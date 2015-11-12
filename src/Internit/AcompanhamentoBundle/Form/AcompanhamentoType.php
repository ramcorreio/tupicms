<?php
namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Internit\AcompanhamentoBundle\Entity\AcompanhamentoEtapa;

class AcompanhamentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('status','checkbox', array(
            'label'    => 'Ativo',
            'required' => false,
        ))
        ->add('percentual','text', array(
            'label'    => 'Percentual Geral',
            'required' => false,
        ))
        ->add('image', new MediaImageType(), array(
            'required' => false,
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\AcompanhamentoBundle\Entity\Acompanhamento',
            'cascade_validation' => true
        ));
    }
    
    public function getName()
    {
        return 'acompanhamento';
    }
}