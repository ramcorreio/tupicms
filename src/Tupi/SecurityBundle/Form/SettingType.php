<?php
namespace Tupi\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class SettingType extends AbstractType 
{	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
		$builder->add('id', 'hidden')
		->add('title', 'text', array(
			'label' => 'Título',
			'required' => true,
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar o título.')),
				new Length(array('max' => 150, 'maxMessage' => 'Limite de caracteres atingido.'))
			)
		))
		->add('description', 'textarea', array(
			'label' => 'Descrição',
			'required' => true,
		    'attr' => array('cols' => 30, 'rows' => 5),
			'constraints' => new NotBlank(array('message' => 'É necessário informar a descrição.'))
		))
		->add('metaKeywords', 'textarea', array(
			'label' => 'Palavras chaves',
			'required' => true,
		    'attr' => array('cols' => 30, 'rows' => 5),
			'constraints' => new NotBlank(array('message' => 'É necessário informar as palavras chaves.'))
		))	
		->add('ga', 'text', array(
		    'label' => 'Google Analytics',
		    'required' => false,
			'attr' => array('placeholder' => 'UA-########-#')
		))
		->add('fbSiteName', 'text', array(
			'label' => 'Nome do site',
			'required' => false
		))
		->add('fbTitle', 'text', array(
		    'label' => 'Título',
		    'required' => false
		))
		->add('fbDescription', 'text', array(
			'label' => 'Descrição',
		    'required' => false
		))
		->add('fbLink', 'text', array(
			'label' => 'Link',
		    'required' => false
		))
		->add('fbImage', 'text', array(
			'label' => 'Imagem',
			'required' => false
		))
		->add('fbId', 'text', array(
			'label' => 'Id Facebook',
			'required' => false,
			'attr' => array('placeholder' => '####################')
		))
		->add('authEmailStatus', 'checkbox', array(
			'label' => 'Ativar E-mail Autenticado',
			'required' => false,
			'attr' => array('class' => 'email-autenticado')
		))
		->add('authEmailPort', 'text', array(
			'label' => 'Porta',
			'required' => false,
			'attr' => array('class' => 'email-porta')
		))		
		->add('authEmail', 'text', array(
			'label' => 'E-mail',
			'required' => false,
			'attr' => array('class' => 'email-email')
		))
		->add('authEmailHost', 'text', array(
				'label' => 'Host',
				'required' => false,
				'attr' => array('class' => 'email-host','placeholder'=>'smtp.site.com.br')
		))		
		->add('authEmailTipo', 'text', array(
				'label' => 'Tipo Segurança',
				'required' => false,
				'attr' => array('class' => 'email-tTipo','placeholder'=>'TLS')
		))		
		->add('authEmailSenha', 'text', array(
			'label' => 'Senha',
			'required' => false,
			'attr' => array('class' => 'email-senha')
		));
	}
	
	public function getName()
	{
		return 'configuration';
	}

}
