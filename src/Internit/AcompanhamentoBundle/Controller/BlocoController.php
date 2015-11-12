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

class BlocoController extends CrudCustomController
{
    const REPOSITORY_NAME = 'InternitAcompanhamentoBundle:Bloco';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitAcompanhamentoBundle:Bloco';
    
    protected $defaultRoute = 'tupi_acompanhamento_bloco_new';
    
    protected function createTypedForm($type)
    {
        return $this->createForm(new BlocoType(), $type);
    }
    
    protected function initObject($id = null) 
    {
        $bloco = new Bloco();
        if(!empty($id)) {
            $bloco = $this->getRepository()->findOneBy(array(
                'id' => $id
            ));
        }
        return $bloco;
    }

    protected function removed(ReturnVal $return)
    {
        $return->setMessage("Bloco excluído com sucesso!");
    }

    protected function save(ReturnJson $return, $id = null, $obj, $form, EntityManager $em)
    {
        $acompanhamento = $this->getRespositoryName(AcompanhamentoController::REPOSITORY_NAME)->find($this->filter);
        $obj->setAcompanhamento($acompanhamento);
        
        if (is_null($obj->getId())) {
            $obj->setCreatedAt(new \DateTime());
            $obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Bloco cadastrada com sucesso!');
        } else {
            $obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Bloco alterada com sucesso!');
        }
    }
}