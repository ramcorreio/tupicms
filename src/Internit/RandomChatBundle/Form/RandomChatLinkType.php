<?php

namespace Internit\RandomChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;
use Tupi\AdminBundle\Types\StatusType;
use Symfony\Component\Validator\Constraints\Url;

class RandomChatLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
         $builder->add('id', 'hidden')
        ->add('chat', 'text', array(
            'label' => 'Link do Chat:',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário preencher um nome.')),
                new Url(array('message' => 'Campo {{ value }} precisa ser uma URL válida.'))
            )
        ));
    }

    public function getName()
    {
        return 'chat_type';
    }
}
