<?php
namespace Internit\TrabalheBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Internit\TrabalheBundle\Controller\GroupController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class GroupType extends AbstractType
{	
	private $_em;
	
	public function __construct(EntityManager $em) {
		$this->_em = $em;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder->add('id', 'hidden')
        ->add('name', 'text', array(
            'label' => 'Nome do Grupo:',
            'required' => true,
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário preencher um nome.'))
            )
        ))
        ->add('emails', 'collection', array(
            'type'         => new GroupEmailType(),
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\TrabalheBundle\Entity\TrabalheGroup'
        ));
    }
    
    public function getName()
    {
        return 'group_type';
    }
}