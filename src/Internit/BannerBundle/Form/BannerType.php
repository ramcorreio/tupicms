<?php
namespace Internit\BannerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\ORM\EntityRepository;
use Tupi\AdminBundle\Types\StatusType;
use Internit\ImovelBundle\Entity\Imovel;
use Symfony\Component\Validator\Constraints\Url;

class BannerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'hidden')
            ->add('name', 'text', array(
            'label' => 'Nome',
            'property_path' => 'name',
            'required' => true,
            'constraints' => new NotBlank(array(
                'message' => 'É necessário informar um nome para o banner.'
            ))
        ))
        ->add('imageDesktop', new ImageBannerType($options['data']->getImageDesktop(), 'Banner'), array(
            'required' => true,
            'label' => 'Imagem desktop - Tamanho: 1270px x 500px'
        ))
        ->add('imageTablet', new ImageBannerType($options['data']->getImageTablet(), 'Banner'), array(
        		'required' => false,
        		'label' => 'Imagem tablet - Tamanho: 980px x 500px'
        ))
        ->add('imageCelular', new ImageBannerType($options['data']->getImageCelular(), 'Banner'), array(
        		'required' => false,
        		'label' => 'Imagem celular - Tamanho: 768px x 500px'
        ))
            ->add('imovel', 'entity', array(
            'label' => 'Imóvel',
            'empty_value' => 'Nenhum imóvel',
            'class' => 'InternitImovelBundle:Imovel',
            'property' => 'name_featured'
        ))
            ->add('visible', 'choice', array(
            'label' => 'Visível',
            'required' => true,
            'choices' => array(
                true => 'Ativada',
                false => 'Desativada'
            ),
            'constraints' => new NotBlank(array(
                'message' => 'É necessário selecionar uma opção de visibilidade'
            ))
        ))
            ->add('target', 'choice', array(
            'label' => 'Abrir em...',
            'required' => true,
            'choices' => array(
				        'blank' => 'Nova janela',
				        'self' => 'Mesma janela'
				    )
				))		
		->add('url', 'text', array(
			'label' => 'URL',
			'required' => true,
            'constraints' => array(
                new Url(array('message' => 'O campo {{ value }} precisa ser uma URL válida.'))
            )
		));
		
    }

    public function getName()
    {
        return 'banner_type';
    }
}
