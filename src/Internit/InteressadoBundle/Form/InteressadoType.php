<?php
namespace Internit\InteressadoBundle\Form;

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
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManager;

class InteressadoType extends AbstractType
{
    private $repository;
    private $imovelId;
    private $_em;
    
    
    public function __construct(EntityRepository $repository, EntityManager $em, $imovelId = null)
    {
        $this->repository = $repository;
        $this->_em = $em;
        $this->imovelId = $imovelId;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$label = "Assunto";
    	$attr = "";
    	$imovel = "";
    	
    	//calculando quantidade de assuntos, caso for maior que 1 o input do assunto ficará invisível
    	$numRowsSubject = $this->repository->allSubjects();
    	if($numRowsSubject <= 1)
    	{
    		$label = false;
    		$attr = "hidden";
    	}
    	
    	if(!is_null($this->imovelId))
    	{
    	   $imovel = $this->_em->getRepository("InternitImovelBundle:Imovel")->find($this->imovelId);
    	}else{
    	   $imovel = $options["data"]->getImovel();
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
        		'attr' => array('class' => $attr),
        		'class' => 'InternitInteressadoBundle:InteressadoSubject',
        		'query_builder' => $queryBuilder,
        		'property' => 'title',
        		'required'    => true,
        		'empty_data'  => null,
        		'constraints' => array(
        				new NotBlank(array('message' => 'Contatar erro ao administrador do site.'))
        		),
        ))
        ->add('imovel', 'entity', array(
            'label' => "Imóvel",
            'attr' => array('class' => $attr),
            'class' => 'InternitImovelBundle:Imovel',
            'property' => 'name',
            'required'    => true,
            'data' => $imovel,
            'constraints' => array(
                new NotBlank(array('message' => 'Contatar erro ao administrador do site.'))
            ),
        ));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    			'data_class' => 'Internit\InteressadoBundle\Entity\Interessado',
    	));
    }
    
    public function getName()
    {
        return 'interessado_type';
    }
}
