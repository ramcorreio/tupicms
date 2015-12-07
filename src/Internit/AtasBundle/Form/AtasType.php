<?php

namespace Internit\AtasBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tupi\ContentBundle\Types\PageStatusType;
use Tupi\SecurityBundle\Validator\Constraints\DuplicatedKey;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Doctrine\ORM\EntityManager;
use Internit\AtasBundle\Controller\AtasController;
use Tupi\ContentBundle\Controller\MediaController;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Internit\AtasBundle\Form\MediaType;

class AtasType extends AbstractType
{	
	private $_em;
	
	public function __construct(EntityManager $em) {
		$this->_em = $em;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$builder->add('id', 'hidden')
		->add('titulo', 'text', array(
			'label' => 'Título',
			'required' => true
		))
		->add('descricao', 'textarea', array(
			'label' => 'Descrição *',
			'required' => false,
		))
		->add('tags', 'text', array(
			'label' => 'Tags',
			'required' => false
		))
		->add('dataVigente', 'text', array(
				'label' => 'Data Vigente',
				'attr' => array('class' => 'form-control data'),
				'required' => false
		))
		->add('arquivo', new MediaType(), array(
				'required' => true,
		))
		->add('active', 'choice', array(
			'label' => 'Visível',
			'required' => true,
			'choices' => array(
				true => 'Ativada',
				false => 'Desativada'
			)
		));		
	}
	
	public function getName()
	{
		return 'atas';
	}
}
