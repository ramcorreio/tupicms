<?php
namespace Internit\TrabalheBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;


class GroupEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text', array(
            'label' => 'E-mail:',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'O campo e-mail não pode estar vazio.')),
                new Email(array(
                		'message' => 'O e-mail {{ value }} não é um e-mail válido.',
                		'checkMX' => true,
                		
                ))
            )
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\TrabalheBundle\Entity\TrabalheGroupEmail'
        ));
    }
    
    public function getName()
    {
        return 'group_email_type';
    }
}