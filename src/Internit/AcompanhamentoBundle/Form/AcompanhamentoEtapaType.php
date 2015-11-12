<?php
namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AcompanhamentoEtapaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('valor', 'hidden')
        ->add('etapa', 'entity', array(
            'class' => 'InternitAcompanhamentoBundle:Etapa',
            'property' => 'name',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('e');
            }
        ));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\AcompanhamentoBundle\Entity\AcompanhamentoEtapa'
        ));
    }
    
    public function getName()
    {
        return 'acompanhamento_acompanhamento_etapa';
    }
}