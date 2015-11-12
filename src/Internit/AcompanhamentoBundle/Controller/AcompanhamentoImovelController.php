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
use Internit\AcompanhamentoBundle\Form\AcompanhamentoType;
use Internit\ImovelBundle\Entity\ImovelImageMedia;
use Internit\AcompanhamentoBundle\Entity\AcompanhamentoImageMedia;
use Internit\AcompanhamentoBundle\Form\Crop;
use Internit\AcompanhamentoBundle\Form\CropCollection;
use Internit\AcompanhamentoBundle\Form\UploadImageHelper;
use Symfony\Component\HttpFoundation\Request;
use Internit\AcompanhamentoBundle\Entity\Relatorio;
use Internit\AcompanhamentoBundle\Form\UploadRelatorioHelper;

class AcompanhamentoImovelController extends CrudCustomController
{
    const REPOSITORY_NAME = 'InternitAcompanhamentoBundle:Acompanhamento';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitAcompanhamentoBundle:AcompanhamentoImovel';
    
    protected $defaultRoute = 'tupi_acompanhamento_imovel_edit';
    
    protected $id_acompanhamento;
    
    protected function createTypedForm($type)
    {
        return $this->createForm(new AcompanhamentoType(), $type);
    }
    
    protected function renderTemplate($form)
    {
    	$percentual = $this->getRespositoryName("InternitAcompanhamentoBundle:Acompanhamento")->find($this->id_acompanhamento);
    	
        return $this->render($this->bundleName . ':' . $this->cadastroTemplate, array(
            "form" => $form->createView(),
        	"acompanhamento" => $percentual,
        ));
    }
    
    protected function initObject($id = null) 
    {
    	$this->id_acompanhamento = $id;
        $acompanhamento = new AcompanhamentoType();
        if(!empty($id)) {
            $acompanhamento = $this->getRepository()->findOneBy(array('id' => $id));
        }
        return $acompanhamento;
    }
    
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Acompanhamento excluída com sucesso!");
    }

    protected function save(ReturnJson $return, $id = null, $obj, $form, EntityManager $em)
    {	
        $file = $form['image']['imagem']->getData();
        
        if(!empty($file))
        {
            $cropCollection = new CropCollection();
    	    $cropCollection->add('admin', new Crop('admin', 200, 200));
    	    $cropCollection->add('acompanhamento_imovel', new Crop('acompanhamento_imovel', 380, 338));
    	    
    	    $image = new AcompanhamentoImageMedia();
    	    
    	    $upload = new UploadImageHelper();
    	    $upload->setFile($file);
    	    $upload->setCrops($cropCollection);
    	    $upload->setImage($image);
    	    
    	    if($upload->doUpload() && $upload->isImage())
    	    {
    	        $upload->createThumbs();
    	        $image = $upload->getImage();
    	    }
    	    
    	    $oldImage = $obj->getImage();
    	    
    	    if(!is_null($oldImage->getId())){
    	        $em->remove($oldImage);
    	    }
    	    
    	    $em->persist($image);
    	    $obj->setImage($image);
        }
        
		if(is_null($obj->getId())) {
			$obj->setCreatedAt(new \DateTime());
			$obj->setUpdatedAt(new \DateTime());
			$em->persist($obj);
			$return->setMessage('Acompanhamento cadastrada com sucesso!');
		}
		else {
			$obj->setUpdatedAt(new \DateTime());
			$em->merge($obj);
			$return->setMessage('Acompanhamento alterada com sucesso!');
		}
	}
}