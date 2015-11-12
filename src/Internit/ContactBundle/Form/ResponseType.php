<?php
namespace Internit\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ResponseType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('name', 'text', array(
            'label' => false,
            'property_path' => 'request.person.name',
            'read_only' => true
        ))
        ->add('email', 'text', array(
            'label' => false,
            'property_path' => 'request.person.email',
            'read_only' => true
        ))
        ->add('telefone', 'text', array(
        		'label' => false,
        		'read_only' => true,
        		'property_path' => 'request.person.telefone'
        ))
        ->add('celular', 'text', array(
        		'label' => false,
        		'read_only' => true,
        		'property_path' => 'request.person.celular'
        ))
        ->add('assunto', 'text', array(
        		'label' => false,
        		'read_only' => true,
        		'property_path' => 'request.subject.title'
        ))
        ->add('conheceu', 'text', array(
        		'label' => false,
        		'read_only' => true,
        		'property_path' => 'request.conheceu'
        ))
        ->add('message', 'textarea', array(
            'label' => false,
            'property_path' => 'request.message',
            'read_only' => true
        ))
        ->add('answer', 'textarea', array(
            'label' => 'Resposta',
            'property_path' => 'message',
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário preencher a resposta para responder.')),
                new Length(array('min' => 5, 'minMessage' => 'Mensagem muito curta. Não parece ser mensagem. Informar no mínimo {{ limit }} caracteres.'))
            )
        ))
        ;
    }
    
    public function getName() 
    {
        return 'response_type';
    }
}