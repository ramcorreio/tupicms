<?php
namespace Internit\OferecaBundle\Form;

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
use Internit\OferecaBundle\Entity\Estado;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;

class OferecaType extends AbstractType
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
        ->add('subject', 'entity', array(
        		'label' => false,
        		'attr' => array('class' => 'form-control'),
        		'class' => 'InternitOferecaBundle:OferecaSubject',
        		'query_builder' => $queryBuilder,
        		'property' => 'title',
        		'required'    => true,
        		'empty_data'  => null,
        		'constraints' => array(
        				new NotBlank(array('message' => 'Erro no subject. Por favor, contatar erro ao administrador do site.'))
        		),
        ))
        ->add('statusUsuario', 'choice', array(
        		'choices' => array(
        				'proprietario'   => 'Proprietário do imóvel',
        				'corretor' => 'Corretor',
        				'outros'=> 'Outros '
        		),
        		'label' => 'Você é',
        		'required' => true,
        		'multiple' => false,
        		'expanded' => true,
        ))
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
        
        ->add('endereco', 'text',array(
        		'label' => 'Endereço do imóvel',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita')
        
        ))
        
        ->add('cep', 'text',array(
        		'label' => 'CEP',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita cep', 'onkeyup'=>'buscaDadosCEP()')
        		//'attr' => array('class' => 'form-control menor intup-right direita cep')
        
        ))
        
        ->add('bairro', 'text',array(
        		'label' => 'Bairro',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita')
        
        ))
        
        ->add('referencia', 'text',array(
        		'label' => 'Ponto de Referência',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita')
        
        ))
        
        ->add('localizacao', 'text',array(
        		'label' => 'Link de localização no Google Maps',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita')
        
        ))
        
        ->add('area', 'text',array(
        		'label' => 'Área (M²)',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita')
        
        ))
        
        ->add('valor', 'text',array(
        		'label' => 'Valor desejado pela venda',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita valor')
        
        ))
        
        ->add('formaPagamento', 'text',array(
        		'label' => 'Forma de pagamento:',
        		'required' => true,
        		'attr' => array('class' => 'form-control menor intup-right direita')
        
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
		->add('arquivos', 'collection', array(
				'type'         => new ArquivoType(),
				'prototype'    => true,
				'allow_add'    => true,
				'allow_delete' => true,
				'by_reference' => false,
		))
		
		->add('observacao', 'textarea', array(
				'label' => 'Comentário e Observação:',
				'required' => true,
				'attr' => array('class' => 'form-control '),
		));
		
		$builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
		$builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
		 
    }

    protected function addElements(FormInterface $form, Estado $estado = null) {
    
    	$form->add('estado', 'entity', array(
    			'empty_value' => 'Estado',
    			'data' => $estado,
    			'class' => 'InternitOferecaBundle:Estado',
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
    			'class' => 'InternitOferecaBundle:Cidade',
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
    	$estado = $this->_em->getRepository('InternitOferecaBundle:Estado')->find($data['estado']);
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
    			'data_class' => 'Internit\OferecaBundle\Entity\Ofereca',
    	));
    }
    
    public function getName()
    {
        return 'ofereca_type';
    }
}
