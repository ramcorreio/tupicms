<?php
namespace Internit\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityRepository;
use Tupi\AdminBundle\Types\StatusType;

class ContactConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('mensagemResposta', 'textarea', array(
            'label' => 'Mensagem de resposta',
            'required' => true,
        	'attr' => array('class' => 'form-control','rows'=>'8'),
            'constraints' => new NotBlank(array('message' => 'É necessário informar uma mensagems.'))
        ));
    }

    public function getName()
    {
        return 'config';
    }
}