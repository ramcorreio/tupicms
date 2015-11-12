<?php

namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

use Internit\ImovelBundle\Controller\ImovelController;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Internit\ImovelBundle\Entity\City;
use Internit\ImovelBundle\Entity\State;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;


class AddressType extends AbstractType
{
	private $_em;
	
	public function __construct(EntityManager $em) {
		$this->_em = $em;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('id', 'hidden')		
		->add('street', 'text', array(
			'label' => 'Rua',
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar a rua.')),
				new Length(array(
					'min' => 3,
					'minMessage' => 'Nome da rua muito curto.'
				))
			)
		))
		->add('number', 'text', array(
			'label' => 'Número',
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar número.'))
			)
		))
		->add('district', 'text', array(
		    'label' => 'Bairro',
		    'constraints' => array(
		        new NotBlank(array('message' => 'É necessário informar o bairro.'))
		    )
		))
		->add('zipcode', 'text', array(
			'label' => 'CEP',
			'attr' => array('class' => 'cep'),
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar o CEP.'))
			)
		));
		
		$builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
		$builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
	}

	protected function addElements(FormInterface $form, State $estado = null)
	{
		$form->add('state', 'entity', array(
				'label' => 'Estado',
				'empty_value' => 'Selecione um estado',
				'data' => $estado,
				'class' => 'InternitImovelBundle:State',
				'mapped' => false)
		);
	
		$cidades = array();
		if ($estado)
		{
			$cidades = $estado->getCities();
		}
	
		$form->add('city', 'entity', array(
				'label' => 'Cidade',
				'empty_value' => 'Selecione uma Cidade',
				'class' => 'InternitImovelBundle:City',
				'choices' => $cidades,
				'required' => true,
				'constraints' => array(
						new NotBlank(array('message' => 'É necessário escolher uma cidade'))
				)
		));
	}
	
	function onPreSubmit(FormEvent $event)
	{
		$form = $event->getForm();
		$data = $event->getData();
		$estado = $this->_em->getRepository('InternitImovelBundle:State')->find($data['state']);
		$this->addElements($form, $estado);
	}
	
	
	function onPreSetData(FormEvent $event)
	{
		$city = $event->getData();
		$form = $event->getForm();
		
		$estado = $city->getCity() ? $city->getCity()->getState() : null;
		$this->addElements($form, $estado);
	}
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Internit\ImovelBundle\Entity\Address',
        ));
    }
	
	public function getName()
	{
		return 'address';
	}
}
