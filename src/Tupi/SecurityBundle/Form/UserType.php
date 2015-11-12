<?php
namespace Tupi\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Tupi\SecurityBundle\Validator\Constraints\DuplicatedKey;
use Tupi\AdminBundle\Twig\EditableChecker;


class UserType extends AbstractType 
{
	private $checker;
	
	private $repository;

	public function __construct(EditableChecker $checker, $repository) {
		$this->checker = $checker;
		$this->repository = $repository;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Tupi\SecurityBundle\Entity\User'
		));
	}
	
    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('id', 'hidden')
		->add('name', 'text', array(
			'label' => 'Nome',
			'required' => true,
			'constraints' => new NotBlank(array('message' => 'É necessário informar o nome.'))
		))
		->add('login', 'text', array(
			'label' => 'Login',
			'read_only' => $this->checker->isEdit(),
			'required' => true,
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar um login para acesso ao sistema.')),
				new Length(array('min' => 5, 'minMessage' => 'Login muito curto.')),
				new DuplicatedKey(array(
					'message' => 'O login {{valor}} já está em uso.',
					'repository' => $this->repository
				))
			)
		))
		->add('birthDate', 'date', array(
			'input' => 'datetime',
			'label' => 'Data de Nascimento',
			'widget' => 'single_text',
			'format' => 'dd/MM/yyyy',
			//'attr' => array('class' => 'form-control'),
			'required' => true,
			'invalid_message' => 'É necessário informar uma data válida.',
			'constraints' => array(
				new NotNull(array('message' => 'É necessário informar uma data.')),
				new Date(array('message' => 'É necessário informar uma data válida.'))
			)
		))
		->add('email', 'text', array(
			'label' => 'Email',
			'required' => true,
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar um email.')),
				new Email(array('message' => 'É necessário informar um email válido.')),
				new DuplicatedKey(array(
					'message' => 'O email {{valor}} já está em uso.',
					'repository' => $this->repository
				))
			)
		))
		->add('role', 'hidden')
		->add('active', 'hidden');
		
		$passRequired = !$this->checker->isEdit();
		
		$passOpts = array(
			'type' => 'password',
			'required' => $passRequired,
			'invalid_message' => 'Os campos de senha devem ser iguais.',
			'first_options'  => array('label' => 'Senha'),
			'second_options' => array('label' => 'Confirmar senha')
		);
		
		if($passRequired) {
			$passOpts['constraints'] = array(
				new NotBlank(array('message' => 'É necessário informar uma senha.')),
				new Length(array('min' => 5, 'minMessage' => 'A senha deve ter mais de 5 caracteres.'))
			);
		}
		
		$builder->add('password', 'repeated', $passOpts);
	}
	
	public function getName()
	{
		return 'user';
	}

}
