<?php
namespace Tupi\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Tupi\SecurityBundle\Entity\Resource;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionCollectionType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder->add('resource', 'hidden', array(
			'property_path' => 'resource.id'
		));
		
		$builder->add('authorizations', 'choice', array(
			'multiple' => true,
			'expanded' => true, 
			'required' => false,
			'choices'  => Authorization::$AUTHORIZATIONS
		));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Tupi\SecurityBundle\Form\Authorization',
		));
	}
	
	public function getName()
	{
		return 'permissionResource';
	}
	
}