<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\AcompanhamentoBundle\Form\GaleriaType;
use Internit\AcompanhamentoBundle\Entity\Galeria;
use Tupi\ContentBundle\Entity\Media;
use Tupi\ContentBundle\Form\MediaFileType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tupi\ContentBundle\Entity\ImageMedia;
use Internit\AcompanhamentoBundle\Form\UploadHelper;

class GaleriaController extends CrudCustomController
{
    const REPOSITORY_NAME = 'InternitAcompanhamentoBundle:Galeria';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitAcompanhamentoBundle:Galeria';
    
    protected $defaultRoute = 'tupi_acompanhamento_galeria_new';
    
    protected $id_acompanhamento;
    
    protected function createTypedForm($type)
    {
        return $this->createForm(new GaleriaType(), $type);
    }
    
    protected function initObject($id = null) 
    {
        $galeria = new Galeria();
        if(!empty($id)) {
            $galeria = $this->getRepository()->findOneBy(array('id' => $id));
        }
        return $galeria;
    }
    
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Galeria excluída com sucesso!");
    }

    protected function save(ReturnJson $return, $id = null, $obj, $form, EntityManager $em)
    {		
        $bloco = $this->getRespositoryName(BlocoController::REPOSITORY_NAME)->find($this->filter);
        $obj->setBloco($bloco);
        
		if(is_null($obj->getId())) {
			$obj->setCreatedAt(new \DateTime());
			$obj->setUpdatedAt(new \DateTime());
			$em->persist($obj);
			$return->setMessage('Galeria cadastrada com sucesso!');
		}
		else {
			$obj->setUpdatedAt(new \DateTime());
			$em->merge($obj);
			$return->setMessage('Galeria alterada com sucesso!');
		}
	}
}