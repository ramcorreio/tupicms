<?php
namespace Internit\InteressadoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Tupi\AdminBundle\Types\StatusType;
use Internit\InteressadoBundle\Controller\SubjectController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


class SubjectType extends AbstractType
{
	private $_em;
	
	public function __construct(EntityManager $em) {
		$this->_em = $em;
	}
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('title', 'text', array(
            'label' => 'Título:',
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário preencher o título.')),
                new Length(array('min' => 5, 'minMessage' => 'Título muito curto. Informar no mínimo {{ limit }} caracteres.'))
            )
        ))
        ->add('description', 'text', array(
            'label' => 'Descrição:',
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário preencher a descrição.')),
                new Length(array('min' => 10, 'minMessage' => 'Descrição muito curta. Informar no mínimo {{ limit }} caracteres.'))
            )
        ))
        ->add('status', 'choice', array(
            'label' => 'Status:',
            'required' => true,
            'choices' => array(
                StatusType::ACTIVE => StatusType::getTypeLabel(StatusType::ACTIVE),
                StatusType::INACTIVE => StatusType::getTypeLabel(StatusType::INACTIVE)
            )
        ))
        ->add('randomStatus', 'choice', array(
            'label' => 'Modo Randômico:',
            'required' => true,
            'choices' => array(
                StatusType::ACTIVE => StatusType::getTypeLabel(StatusType::ACTIVE),
                StatusType::INACTIVE => StatusType::getTypeLabel(StatusType::INACTIVE)
            )
        ))
        ->add('groups', 'entity', array(
        		'label' => 'Grupos:',
        		'class' => "InternitInteressadoBundle:InteressadoGroup",
        		'required' => true,
    			'multiple' => true,
    			'expanded' => true,
    	));        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\InteressadoBundle\Entity\InteressadoSubject',
        ));
    }
    
    public function getName() 
    {
        return 'subjectInteressado_type';
    }
}