<?php
namespace Internit\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Tupi\SecurityBundle\Validator\Constraints\DuplicatedKey;

use Doctrine\ORM\EntityManager;

use Internit\NewsletterBundle\Controller\NewsletterController;
use Symfony\Component\Validator\Constraints\Length;

class NewsletterType extends AbstractType
{
	private $_em;
	
	public function __construct(EntityManager $em) {
		$this->_em = $em;
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', 'text', array(
            'label' => 'Nome',
        	'required' => true,
        	'attr' => array('class' => 'form-control'),
			'constraints' => array(
					new NotBlank(array('message' => 'É necessário informar um nome.')),
			)
        		
        ))
        ->add('email', 'email', array(
            'label' => 'E-mail',
        	'required' => true,
        	'attr' => array('class' => 'form-control'),
        	'constraints' => array(     
					new NotBlank(array('message' => 'É necessário informar um e-mail.')),   	
					new DuplicatedKey(array(
						'message' => 'O e-mail "{{valor}}" já está cadastrado.',
						'repository' => $this->_em->getRepository(NewsletterController::REPOSITORY_NAME)
					)),
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
        ;
    }
    
    public function getName()
    {
        return 'newsletter';
    }
}