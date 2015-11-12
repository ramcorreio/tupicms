<?php
namespace Internit\AcompanhamentoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Internit\AcompanhamentoBundle\Controller\GaleriaController;
use Doctrine\ORM\EntityManager;
use Tupi\SecurityBundle\Validator\Constraints\DuplicatedKey;
use Internit\ImovelBundle\Entity\Imovel;
use Tupi\ContentBundle\Entity\ImageMedia;
use Tupi\ContentBundle\Form\MediaFileType;

class GaleriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
        ->add('name', 'text', array(
            'label' => 'Nome',
			'attr' => array('maxlength' => 100),
			'constraints' => array(
				new NotBlank(array('message' => 'É necessário informar o nome da galeria.')),
				new Length(array(
					'min' => 3, 
					'max' => 100,
					'minMessage' => 'Nome da galeria muito curto.',
					'maxMessage' => 'Nome da galeria muito longo.'
				))
			)
        ));
    }
    
    public function getName()
    {
        return 'galeria_type';
    }
}