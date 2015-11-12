<?php
namespace Internit\TrabalheBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityRepository;
use Tupi\AdminBundle\Types\StatusType;
use Internit\ContactBundle\Form\MediaType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Internit\TrabalheBundle\Entity\Estado;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;

class TrabalheType extends AbstractType
{
    private $repository;
    
    public function __construct(EntityRepository $repository, EntityManager $em)
    {
        $this->repository = $repository;
        $this->_em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$label = "Assunto";
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

        $builder
        ->add('id','hidden')
        ->add('nome', 'text', array(
            'label' => 'Nome Completo',
            'property_path' => 'person.name',
            'required' => true,
        	'attr' => array('class' => 'form-control'),
            'constraints' => new NotBlank(array('message' => 'É necessário informar um nome válido.'))
        ))
        ->add('email', 'text',array(
            'label' => 'Email',
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
        	'attr' => array('class' => 'form-control maskphone'),
            'property_path' => 'person.telefone',
        ))
        ->add('celular', 'text',array(
        		'label' => 'DDD + Celular',
        		'required' => true,
        		'attr' => array('class' => 'form-control maskphone'),
        		'property_path' => 'person.celular',
        ))
        ->add('subject', 'entity', array(
        		'label' => 'Área de Interesse',
        		'attr' => array('class' => 'form-control'),
        		'class' => 'InternitTrabalheBundle:TrabalheSubject',
        		'query_builder' => $queryBuilder,
        		'property' => 'title',
        		'required'    => true,
        		'empty_data'  => null,
        		'constraints' => array(
        				new NotBlank(array('message' => 'Contatar erro ao administrador do site.'))
        		),
        ))
        ->add('curriculo', new MediaType(true,
        			array("application/msword", "application/pdf", "application/x-pdf", "application/vnd.ms-office", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint")
        		), array(
        		'label' => false,
        		'required' => true,
        		'attr' => array('class' => 'form-control'),
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
        ->add('sexo', 'choice', array(
        		'attr' => array('class' => 'selecionar-trabalhe intup-left'),
        		'choices' => array(
        				'masculino'   => 'Masculino',
        				'feminino' => 'Feminino',
        		),
        		'multiple' => false,
        		'expanded' => true,
        		'required' => true
        ))
        
        ->add('escolaridade', 'choice', array(
                'empty_data' => null,
                'empty_value' => 'Selecione',
            	'choices' => array(
        				'fundamental_in'   => 'Ensino Fundamental Incompleto',
        				'fundamental' => 'Ensino Fundamental Completo',
        				'medio_in' => 'Ensino Médio Incompleto',
        				'medio' => 'Ensino Médio Completo',
        				'faculdade_in' => '3º Grau Incompleto',
        				'faculdade' => '3º Grau Completo',
        				'pos_in' => 'Pós Graduação Incompleto',
        				'pos' => 'Pós Graduação Completo'
        		),
        		'label' => 'Escolaridade',
        		'required' => true,
        		'attr' => array('class' => 'form-control'),
        ))
		
		->add('nascimento', 'date', array(
				'attr' => array('class' => 'form-control data'),
				'label' => 'Data de Nascimento',
				'required'    => true,
				'widget' => 'single_text',
				'format' => 'dd/MM/yyyy',
				'property_path' => 'person.nascimento',
		))
		
		->add('bairro', 'text',array(
				'label' => 'Bairro',
				'required' => true,
				'attr' => array('class' => 'form-control menor intup-right direita')
				
		))
		
		/* ->add('cidade', 'text',array(
				'label' => 'Cidade',
				'required' => true,
				'attr' => array('class' => 'form-control'),
				'constraints' => new NotBlank(array('message' => 'É necessário informar um nome válido.'))
		))
		
		
		->add('estado', 'entity', array(
				'empty_value' => 'Estado',
				'class' => 'InternitTrabalheBundle:Estado',
				'mapped' => false,
				'property' => 'uf',
				'required' => true,
		)) */
		
		->add('facebook', 'text', array(
				'label' => 'Facebook',
				'required' => false,
				'attr' => array('class' => 'form-control'),
		))
		
		->add('linkedin', 'text', array(
				'label' => 'Linkedin',
				'required' => false,
				'attr' => array('class' => 'form-control'),
		))
		
		->add('informativo', 'choice', array(
				'label' => 'Gostaria de Receber nossos informativos?',
				'choices' => array(
						'sim'   => 'Sim',
						'nao' => 'Não',
				),
				'required' => true,
				'multiple' => false,
				'expanded' => true,
		))
		->add('faixa_salarial', 'text', array(
				'label' => 'Faixa Salarial',
				'required' => true,
				'attr' => array('class' => 'form-control valor'),
		));
		
		$builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
		$builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
		 
    }

    protected function addElements(FormInterface $form, Estado $estado = null) {
    
    	$form->add('estado', 'entity', array(
    			'empty_value' => 'Estado',
    			'data' => $estado,
    			'class' => 'InternitTrabalheBundle:Estado',
    			'mapped' => false,
    			'property' => 'uf',
    			'required' => true,
    			'attr' => array('class' => 'form-control'),
    	)
    	);
    
    	$cidades = array();
    	if ($estado) {
    		$cidades = $estado->getCidades();
    	}
    
    	$form
    	->add('cidade', 'entity', array(
    			'empty_value' => 'Cidade',
    			'class' => 'InternitTrabalheBundle:Cidade',
    			'choices' => $cidades,
    			'required' => true,
    			'constraints' => array(
    					new NotBlank(array('message' => 'É necessário escolher uma cidade'))
    			),
    			'attr' => array('class' => 'form-control'),
    	));
    }
    
    function onPreSubmit(FormEvent $event) {
    	$form = $event->getForm();
    	$data = $event->getData();
    	$estado = $this->_em->getRepository('InternitTrabalheBundle:Estado')->find($data['estado']);
    	$this->addElements($form, $estado);
    }
    
    function onPreSetData(FormEvent $event) {
    	$data = $event->getData();
    	$form = $event->getForm();
    
    	if(is_null($data) || is_null($data->getId())){
    		$this->addElements($form);
    	}else {
    		$this->addElements($form, $data->getCidade()->getEstado());
    	}
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'Internit\TrabalheBundle\Entity\Trabalhe',
    	));
    }
    
    public function getName()
    {
        return 'trabalhe_type';
    }
}
