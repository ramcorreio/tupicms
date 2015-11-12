<?php
namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class EtapaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('name', 'text', array(
            'label' => 'Etapa',
            'required' => false
        ));
    }
    
    public function getName()
    {
        return 'etapa';
    }
}