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

class ContactType extends AbstractType
{
    private $repository;
    
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$label = "Departamento";
    	$attr = "";
    	
    	//calculando quantidade de assuntos, caso for maior que 1 o input do assunto ficará invisível
    	$numRowsSubject = $this->repository->allSubjects();
    	if($numRowsSubject <= 1)
    	{
    		$label = false;
    		$attr = "hidden";
    	}
    	 
        $queryBuilder = $this->repository->createQueryBuilder("s");
        $queryBuilder->where('s.status = :status');
        $queryBuilder->setParameter('status', StatusType::ACTIVE);
        $queryBuilder->andWhere('s.title != :title');
        $queryBuilder->setParameter('title', 'Trabalhe');
        
        $builder->add('nome', 'text', array(
            'label' => 'Nome Completo',
            'property_path' => 'person.name',
            'required' => true,
        	'attr' => array('class' => 'form-control'),
            'constraints' => new NotBlank(array('message' => 'É necessário informar um nome válido.'))
        ))
        ->add('email', 'text',array(
            'label' => 'E-mail',
            'property_path' => 'person.email',
            'required' => true,
        	'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário informar um e-mail.')),
                new Email(array('message' => 'É necessário informar um e-mail válido.'))
            )
        ))
        ->add('telefone', 'text',array(
            'label' => 'DDD + Telefone',
            'required' => false,
        	'attr' => array('class' => 'form-control maskphone'),
            'property_path' => 'person.telefone',
        ))
        ->add('celular', 'text',array(
        		'label' => 'DDD + Celular',
        		'required' => false,
        		'attr' => array('class' => 'form-control maskphone'),
        		'property_path' => 'person.celular',
        ))
		->add('subject', 'entity', array(
				'label' => $label,
				'attr' => array('class' => 'form-control '.$attr),
				'class' => 'InternitContactBundle:ContactSubject',
				'query_builder' => $queryBuilder,
				'property' => 'title',
				'required'    => true,
				'empty_value'       =>  $label,
        		'empty_data'        => null,
	            'constraints' => array(
	                new NotBlank(array('message' => 'Contatar erro ao administrador do site.'))
	            ),
		))
		->add('conheceu', 'choice', array(
				'choices' => array(
						'google'   => 'Google',
						'email' => 'Email',
						'revista' => 'Revista',
						'placas' => 'Placas',
						'jornais' => 'Jornais',
						'outros' => 'Outros'
				),
				'label' => 'Como ficou sabendo da empresa?',
				'attr' => array('class' => 'form-control'),
		))
		->add('message', 'textarea', array(
	        'label' => 'Mensagem',
	        'required' => true,
        	'attr' => array('class' => 'form-control'),
	        'constraints' => new Length(array('min' => 5, 'minMessage' => 'Mensagem muito curta. Não parece ser mensagem. Informar no mínimo {{ limit }} caracteres.'))
		))
		->add('informativo', 'choice', array(
				'label' => 'Gostaria de Receber nossos informativos?',
				'choices' => array(
						'sim'   => 'Sim',
						'nao' => 'Não',
				),
				'multiple' => false,
				'expanded' => true,
		));
		
    }

    public function getName()
    {
        return 'fale_conosco_type';
    }
}
