<?php
namespace Internit\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('name', 'text', array(
            'label' => false,
            'read_only' => true
        ))
        ->add('email', 'text', array(
            'label' => false,
            'read_only' => true
        ))
        ->add('telefone', 'text', array(
            'label' => false,
            'read_only' => true
        ))
        ->add('celular', 'text', array(
            'label' => false,
            'read_only' => true
        ))
        ->add('nascimento', 'date', array(
        		'label' => false,
        		'widget' => 'single_text',
        		'format' => 'dd/MM/yyyy',
        		'read_only' => true
        ))
        ->add('createdAt', 'date', array(
            'label' => false,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'read_only' => true
        ))
        ->add('updatedAt', 'date', array(
            'label' => false,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'read_only' => true
        ))
        ;
    }
    
    public function getName()
    {
        return 'person_type';
    }
}