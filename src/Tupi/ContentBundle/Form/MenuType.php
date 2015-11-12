<?php
namespace Tupi\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tupi\AdminBundle\Twig\Template\ChangePageEvent;

class MenuType extends AbstractType
{
    private $changeEvent;
    
    public function __construct(ChangePageEvent $changeEvent) {
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
		->add('position', 'hidden')
		->add('title', 'text', array(
			'label' => 'Título',
			'required' => true,
			'constraints' => new NotBlank(array('message' => 'É necessário informar o título'))
		))
		->add('url', 'text', array(
			'label' => 'URL',
			'required' => false,
		    'read_only' => true
		))
		->add('pages', 'entity', array(
		    'label' => 'Páginas',
		    'class' => 'TupiContentBundle:Page',
		    'property' => 'title',
		    'expanded' => true,
		    'multiple' => true
		))
		->add('templateName', 'choice', array(
			'label' => 'Template',
			'choices'   => $choices,
			'required' => false
		))
		->add('parent', 'entity', array(
			'label' => false,
			'class' => 'TupiContentBundle:Menu',
			'property' => 'title',
			'required'    => false,
			'empty_value' => 'Nenhum',
			'empty_data'  => null,
			'read_only' => true,
			'attr' => array('class' => 'hide')
		))
		->add('active', 'checkbox', array(
			'label' => 'Ativo',
			'required' => false
		))
		->add('redirect', 'checkbox', array(
				'label' => 'Redirecionar',
				'required' => false
		))
		->add('menuRedirect', 'entity', array(
				'label' => false,
				'class' => 'TupiContentBundle:Menu',
				'property' => 'title',
				'required'    => false,
				'empty_value' => 'Escolha o menu',
				'attr' => array('class' => 'hide')
		));
		
	}
	
	public function getName()
	{
		return 'menu';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'Tupi\ContentBundle\Entity\Menu'
	    ));
	}
}