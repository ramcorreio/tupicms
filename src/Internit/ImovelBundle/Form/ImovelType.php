<?php

namespace Internit\ImovelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use Tupi\ContentBundle\Types\PageStatusType;
use Tupi\SecurityBundle\Validator\Constraints\DuplicatedKey;
use Tupi\ContentBundle\Controller\MediaController;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Internit\ImovelBundle\Controller\ImovelController;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Tupi\ContentBundle\Form\MediaFileType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Internit\ImovelBundle\Form\MediaType;

class ImovelType extends AbstractType
{	
	private $_em;
	
	public function __construct(EntityManager $em) {
		$this->_em = $em;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('id', 'hidden')
		->add('name', 'text', array(
			'label' => 'Nome',
			'attr' => array('maxlength' => 150),
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar o nome.'))
			)
		))
		->add('nameFeatured', 'text', array(
		    'label' => 'Nome de Destaque',
		    'attr' => array('maxlength' => 20),
		    'constraints' => array(
		        new NotBlank(array('message' => 'É necessário informar o nome de destaque.')),
		        new Length(array(
		            'min' => 5,
		            'max' => 20,
		            'minMessage' => 'Nome muito curto.',
		            'maxMessage' => 'Nome muito longo.'
		        ))
		    )
		))
        ->add('url', 'text', array(
        		'label' => 'Url',
        		'attr' => array('class' => 'url', 'readonly' => 'readonly'),
        		'required' => false,
        ))	
        ->add('address', new AddressType($this->_em))
        ->add('description', 'ckeditor', array(
			'label' => 'Descrição',
		    'attr' => array('class' => 'nofloat'),
	        'config' => array(
                'toolbar' => array(
	                array('name' => 'clipboard', 'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo')),
                    array('name' => 'editing', 'items' => array('Scayt')),
                    array('name' => 'links', 'items' => array('Link', 'Unlink')),
                    array('name' => 'insert', 'items' => array('Table', 'HorizontalRule', 'SpecialChar')),
                    array('name' => 'basicstyles', 'items' => array('Bold', 'Italic', 'Strike', '-', 'RemoveFormat')),
                    array('name' => 'document', 'items' => array('Source')),
                    array('name' => 'others', 'items' => array('-')),
                    '/',
                    array('name' => 'paragraph', 'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote')),
                    array('name' => 'styles', 'items' => array('Styles', 'Format'))                    
                ),
	        ),
		    'constraints' => new NotBlank(array('message' => 'É necessário informar uma descrição.'))
		))
		->add('promo', 'text', array(
			'label' => 'Promoções',
			'required' => false
		))
		->add('resume', 'text', array(
			'label' => 'Resumo',
			'required' => true,
		    'attr' => array('maxlength' => 60),
		    'constraints' => array(
		        new NotBlank(array('message' => 'É necessário informar o resumo.')),
		        new Length(array(
		            'max' => 60,
		            'maxMessage' => 'Resumo muito longo.'
		        ))
		    )
		))
		->add('hotsite', 'text', array(
            'label' => 'Link do hotsite',
            'required' => false,
            'constraints' => array(
                new Url(array('message' => 'O campo {{ value }} precisa ser uma URL válida.'))
            )
        ))
		->add('corretor', 'text', array(
            'label' => 'Link do corretor',
            'required' => false,
            'constraints' => array(
                new Url(array('message' => 'O campo {{ value }} precisa ser uma URL válida.'))
            )
        )) 
        ->add('forecast', 'date', array(
            'label' => 'Previsão de entrega',
		    'widget' => 'single_text',
		    'format' => 'dd/MM/yyyy',
            'required' => false,
        	'attr' => array('class' => 'datepicker'),
        ))
        ->add('done', 'date', array(
            'label' => 'Data de conclusão',
		    'widget' => 'single_text',
		    'format' => 'dd/MM/yyyy',
            'required' => false,
        	'attr' => array('class' => 'datepicker'),
        ))
		->add('visible', 'choice', array(
			'label' => 'Visível',
			'required' => true,
			'choices' => array(
				true => 'Ativada',
				false => 'Desativada'
			),
			'constraints' => new NotBlank(array('message' => 'É necessário selecionar uma opção de visibilidade'))
		))
		->add('destaque', 'choice', array(
				'label' => 'Destaque na home',
				'choices' => array(
						true => 'Sim',
						false => 'Não'
				),
		))		
        ->add('videos', 'collection', array(
            'type'         => new ImovelVideoType(),
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
        ))
       	->add('status', 'entity', array(
       	 		'label' => 'Status',
        		'class' => "InternitImovelBundle:ImovelStatus",
        		'required' => true,
        		'empty_value' => 'Nenhuma'
        ))
        ->add('tags', 'entity', array(
        		'label' => 'Tags',
        		'class' => "InternitImovelBundle:ImovelTag",
        		'required' => true,
    			'multiple' => true,
    			'expanded' => true,
    	))
        ->add('makers', 'entity', array(
        		'label' => 'Realizadores',
        		'class' => "InternitImovelBundle:ImovelMakers",
        		'required' => true,
    			'multiple' => true,
    			'expanded' => true,
    	))    	
		->add('images', 'collection', array(
				'type'         => new ImageType(),
				'prototype'    => true,
				'allow_add'    => true,
				'allow_delete' => true,
				'by_reference' => false
		))
		->add('type', 'choice', array(
		    'label' => 'Tipo',
		    'required' => true,
		    'choices'  => array(
		        'Residencial' => 'Residencial',
		        'Comercial' => 'Comercial',
		        'Hotel' => 'Hotel',
		        'Híbrido' => 'Híbrido',
		    ))
		)
		->add('textLocation', 'text', array(
		    'label' => 'Texto de Localização',
		    'required' => false,
		))
		->add('arquivo', new MediaType())
		->add('imagemDestaque', new ImageDestaqueType($options['data']->getImagemDestaque(),'Destaque'),array(
		    'required' => true))
		->add('logo', new ImageDestaqueType($options['data']->getLogo(),'Logo'),array(
		    'required' => true))
		->add('banner', new ImageDestaqueType($options['data']->getLogo(),'Banner'),array(
		    'required' => true, 'label' =>'Imagem da Ficha do imóvel'));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Internit\ImovelBundle\Entity\Imovel'
		));
	}
	
	public function getName()
	{
		return 'imovel';
	}
}
