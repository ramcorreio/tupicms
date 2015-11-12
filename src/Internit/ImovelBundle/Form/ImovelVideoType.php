<?php
namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class ImovelVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'Título',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'O campo título não pode estar vazio.'))
            )
        ))
        ->add('video', 'text', array(
            'label' => 'Url',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário ter uma URL')),
                new Url(array('message' => 'Campo {{ value }} precisa ser uma URL válida.'))
            )
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\ImovelBundle\Entity\ImovelVideo'
        ));
    }
    
    public function getName()
    {
        return 'imovel_video';
    }
}