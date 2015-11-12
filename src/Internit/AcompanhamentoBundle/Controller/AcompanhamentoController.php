<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\AcompanhamentoBundle\Form\EtapaType;
use Internit\AcompanhamentoBundle\Entity\Etapa;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Internit\AcompanhamentoBundle\Form\UploadHelper;
use Tupi\ContentBundle\Entity\ImageMedia;
use Internit\AcompanhamentoBundle\Form\AcompanhamentoType;
use Internit\AcompanhamentoBundle\Entity\Acompanhamento;
use Symfony\Component\HttpFoundation\Request;
use Internit\AcompanhamentoBundle\Form\BlocoType;
use Internit\AcompanhamentoBundle\Entity\Bloco;
use Tupi\AdminBundle\Controller\CrudController;

class AcompanhamentoController extends CrudController
{
    const REPOSITORY_NAME = 'InternitAcompanhamentoBundle:Acompanhamento';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitAcompanhamentoBundle:Acompanhamento';
    
    protected $defaultRoute = 'tupi_acompanhamento_home';
    
    protected function createTypedForm($type)
    {
        return $this->createForm(new AcompanhamentoType(), $type);
    }
    
    protected function initObject($id = null) 
    {
        $acompanhamento = new Acompanhamento();
        if(!empty($id)) {
            $acompanhamento = $this->getRepository()->findOneBy(array(
                'id' => $id
            ));
        }
        return $acompanhamento;
    }

    protected function removed(ReturnVal $return)
    {
        $return->setMessage("Acompanhamento excluído com sucesso!");
    }

    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        if (is_null($obj->getId())) {
            $obj->setCreatedAt(new \DateTime());
            $obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Acompanhamento cadastrada com sucesso!');
        } else {
            $obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Acompanhamento alterada com sucesso!');
        }
    }
    
    public function sortAction()
    {
    	$position = 1;
    
    	$em = $this->getDoctrine()->getManager();
    		
    	foreach ($this->getRequest()->get('data') as $id)
    	{
    		$obj = $this->getRepository()->findOneBy(array('id' => $id));
    		$obj->setPosition($position++);
    
    		$em->merge($obj);
    	}
    	 
    	$em->flush();
    
    	return new JsonResponse(array("success" => true, "message" => "Imóveis reordenados com sucesso!"));
    }
}