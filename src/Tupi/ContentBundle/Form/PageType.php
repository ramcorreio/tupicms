<?php
namespace Tupi\ContentBundle\Form;

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
use Tupi\ContentBundle\Controller\PageController;
use Tupi\ContentBundle\Controller\MediaController;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Tupi\AdminBundle\Twig\Template\ChangePageEvent;

class PageType extends AbstractType
{
	private $_em;
	
	private $changeEvent;
	
	public function __construct(EntityManager $em, ChangePageEvent $changeEvent) {
		$this->_em = $em;
		$this->changeEvent = $changeEvent;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{		
		$choices = array();
		$choices = array('' => 'Nenhum');
		
		if($this->changeEvent != null) {
				
			foreach ($this->changeEvent->getTemplates() as $template){
				
				$choices[$template->serialize()] = $template->getLabel();
			}
		}
		
		$builder->add('id', 'hidden')
		->add('title', 'text', array(
			'label' => 'Título da página',
			'required' => true,
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar o título')),
				new Length(array(
					'min' => 3, 
					'max' => 150,
					'minMessage' => 'Título muito curto.',
					'maxMessage' => 'Login muito longo.'
				)),
				new DuplicatedKey(array(
					'message' => 'Já exite uma página com o título "{{valor}}".',
					'repository' => $this->_em->getRepository(PageController::REPOSITORY_NAME)
				))
			)
		))
		->add('summary', 'textarea', array(
			'label' => 'Resumo',
			'required' => false,
			'attr' => array('cols' => 30, 'rows' => 5),
			'constraints' => array(
				new Length(array('max' => 1024, 'maxMessage' => 'Login muito longo.')),
			)
		))
		->add('source', 'text', array(
			'label' => 'Fonte',
			'required' => false
		))
		->add('url', 'text', array(
			'label' => 'URL da página (Relativo à URL base do site)',
			'required' => false,
			'read_only' => true
		))
		->add('status', 'choice', array(
			'label' => 'Status',
			'required' => true,
			'choices' => array(
				PageStatusType::PUBLISHED => PageStatusType::getTypeLabel(PageStatusType::PUBLISHED),
				PageStatusType::TO_REVIEW => PageStatusType::getTypeLabel(PageStatusType::TO_REVIEW),
				PageStatusType::DRAFT => PageStatusType::getTypeLabel(PageStatusType::DRAFT)
			),
			'constraints' => new NotBlank(array('message' => 'É necessário selecionar um status'))
		))
		->add('visible', 'choice', array(
			'label' => 'Visível no menu',
			'required' => true,
			'choices' => array(
				true => 'Ativada',
				false => 'Desativada'
			),
			'constraints' => new NotBlank(array('message' => 'É necessário selecionar uma opção de visibilidade'))
		))
		->add('home', 'checkbox', array(
			'label' => 'Página inicial:',
			'required' => false
		))
		->add('body', 'ckeditor', array(
			'label' => 'Conteúdo',
			'required' => true,
		    'attr' => array('class' => 'nofloat'),
	        'config' => array(
                'toolbar' => array(
	                array('name' => 'clipboard', 'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo')),
                    array('name' => 'editing', 'items' => array('Scayt')),
                    array('name' => 'links', 'items' => array('Link', 'Unlink')),
                    array('name' => 'insert', 'items' => array('Image', 'Table', 'HorizontalRule', 'SpecialChar')),
                    array('name' => 'basicstyles', 'items' => array('Bold', 'Italic', 'Strike', '-', 'RemoveFormat')),
                    array('name' => 'document', 'items' => array('Source')),
                    array('name' => 'others', 'items' => array('-')),
                    '/',
                    array('name' => 'paragraph', 'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote')),
                    array('name' => 'styles', 'items' => array('Styles', 'Format'))                    
                ),
                'filebrowserBrowseRoute'           => 'tupi_media_images',
                'filebrowserBrowseRouteAbsolute'   => true,
	        ),
		    'constraints' => new NotBlank(array('message' => 'É necessário informar o conteúdo da página'))
		))
		->add('header', 'text', array(
			'label' => 'Cabeçalho da página',
			'required' => false,
			'constraints' => new Length(array('min' => 5, 'minMessage' => 'Cabeçalho da página muito curto. É necessário o mínimo de {{ limit }} caracteres.'))
		))
		->add('metaKeywords', 'textarea', array(
			'label' => 'Palavras-chave',
			'required' => false,
			'attr' => array('cols' => 30, 'rows' => 5),
			'constraints' => new Length(array('min' => 10, 'minMessage' => 'Palavras-chave muito curto. É necessário o mínimo de {{ limit }} caracteres.'))
		))
		->add('description', 'textarea', array(
			'label' => 'Descrição',
			'required' => false,
			'attr' => array('cols' => 30, 'rows' => 5),
			'constraints' => new Length(array('min' => 10, 'minMessage' => 'Descrição muito curta. É necessário o mínimo de {{ limit }} caracteres.'))
		))
		->add('images', 'entity', array(
			'class' => 'TupiContentBundle:ImageMedia',
			'property' => 'label',
			'expanded' => true,
			'multiple' => true
		))
		->add('templateName', 'choice', array(
			'label' => 'Template',
			'choices'   => $choices,
			'required' => false
		))
		;
		
	}
	
	public function getName()
	{
		return 'page';
	}
}