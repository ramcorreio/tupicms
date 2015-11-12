<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\AcompanhamentoBundle\Form\EtapaType;
use Internit\AcompanhamentoBundle\Entity\Etapa;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;

class EtapaController extends CrudController
{
    const REPOSITORY_NAME = 'InternitAcompanhamentoBundle:Etapa';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitAcompanhamentoBundle:Etapa';
    
    protected $defaultRoute = 'tupi_etapa_home';
    
    protected function createTypedForm($type)
    {
        return $this->createForm(new EtapaType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {
        $etapa = new Etapa();
        if(!empty($id)) {
            $etapa = $this->getRepository()->findOneBy(array('id' => $id));
        }
        return $etapa;
    }
    
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Etapa excluído com sucesso!");
	}
	    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
        //criar um novo assunto
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Etapa cadastrada com sucesso!');
        }
        //alterar
        else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Etapa alterada com sucesso!');
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
            $this->getRespositoryName("InternitAcompanhamentoBundle:AcompanhamentoEtapa")->atualizarPosicoes($id, $position);
            $em->merge($obj);
        }
         
        $em->flush();
    
        return new JsonResponse(array("success" => true, "message" => "Etapas reordenadas com sucesso!"));
    }
}