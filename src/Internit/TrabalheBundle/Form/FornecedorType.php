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
use Internit\ContactBundle\Form\MediaType;

class FornecedorType extends AbstractType
{
    private $repository;
    
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
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
        $queryBuilder->andWhere('s.title = :title');
        $queryBuilder->setParameter('title', 'Fornecedor');

        $builder
        ->add('id','hidden')
        ->add('subject', 'entity', array(
        		'label' => false,
        		'attr' => array('class' => 'hidden form-control '.$attr),
        		'class' => 'InternitContactBundle:ContactSubject',
        		'query_builder' => $queryBuilder,
        		'property' => 'title',
        		'required'    => true,
        		'empty_data'  => null,
        		'constraints' => array(
        				new NotBlank(array('message' => 'Contatar erro ao administrador do site.'))
        		),
        ))
        ->add('nome_fantasia', 'text',array(
        		'attr' => array('class' => 'form-control'),
        		'label' => 'Nome Fantasia',
        		'required' => true
        ))
        ->add('razao_social', 'text',array(
        		'attr' => array('class' => 'form-control'),
        		'label' => 'Razão Social',
        		'required' => true
        ))
        ->add('cnpj', 'text', array(
        		'label' => 'CNPJ',
        		'attr' => array('class' => 'form-control intup-left menor cnpj'),
        ))
        ->add('ano_fundacao', 'text', array(
        		'attr' => array('class' => 'form-control intup-left menor ano'),
        		'label' => 'Ano da Fundação',
        		'required' => true,
        ))
        ->add('inscricao_estadual', 'text', array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Inscrição Estadual',
        		'required' => true,
        ))
        ->add('inscricao_municipal', 'text', array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Inscrição Municipal',
        		'required' => true,
        ))
        ->add('tipo_empresa', 'choice', array(
        		'attr' => array('class' => 'selecionar-trabalhe intup-left'),
        		'choices' => array(
        				'micro'   => 'Micro',
        				'media' => 'Média',
        				'grande' => 'Grande',
        				'multinacional' => 'Multinacional'
        		),
        		'multiple' => false,
        		'expanded' => false
        ))
        ->add('ramo_atividade', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Ramo de Atividade',
        		'required' => true
        ))
        ->add('site', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Site',
        		'required' => true
        ))
        ->add('cep', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor cep'),
        		'label' => 'CEP',
        		'required' => true
        ))
        ->add('endereco', 'text',array(
        		'attr' => array('class' => 'form-control'),
        		'label' => 'Endereço',
        		'required' => true
        ))
        ->add('numero', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Numero',
        		'required' => true
        ))
        ->add('estado', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Estado',
        		'required' => true
        ))
        ->add('cidade', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Cidade',
        		'required' => true
        ))
        ->add('bairro', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Bairro',
        		'required' => true
        ))        
        ->add('nome', 'text', array(
            'label' => 'Nome',
            'property_path' => 'person.name',
            'required' => true,
        	'attr' => array('class' => 'form-control'),
            'constraints' => new NotBlank(array('message' => 'É necessário informar um nome válido.'))
        ))
        ->add('cargo', 'text',array(
        		'attr' => array('class' => 'form-control intup-left menor'),
        		'label' => 'Cargo',
        		'required' => true
        ))
        ->add('telefone', 'text',array(
        		'label' => 'DDD + Telefone',
        		'required' => true,
        		'attr' => array('class' => 'form-control maskphone intup-left menor'),
        		'property_path' => 'person.telefone',
        ))
        ->add('email', 'text',array(
            'label' => 'Email',
            'property_path' => 'person.email',
            'required' => true,
        	'attr' => array('class' => 'form-control menor'),
            'constraints' => array(
                new NotBlank(array('message' => 'É necessário informar um e-mail.')),
                new Email(array('message' => 'É necessário informar um e-mail válido.'))
            )
        ));
    }

    public function getName()
    {
        return 'fornecedor_type';
    }
}
