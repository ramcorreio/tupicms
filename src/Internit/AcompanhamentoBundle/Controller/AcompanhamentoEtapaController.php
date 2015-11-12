<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tupi\ContentBundle\Entity\ImageMedia;
use Internit\AcompanhamentoBundle\Form\UploadHelper;
use Symfony\Component\HttpFoundation\Request;
use Internit\AcompanhamentoBundle\Entity\Etapa;
use Internit\AcompanhamentoBundle\Entity\Acompanhamento;
use Internit\AcompanhamentoBundle\Entity\AcompanhamentoEtapa;

class AcompanhamentoEtapaController extends Controller
{
    public function indexAction($filter)
    {
        $etapas = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:AcompanhamentoEtapa')->listForm($filter);
        return $this->render('InternitAcompanhamentoBundle:AcompanhamentoEtapa:cadastro.html.twig', array(
            "etapas" => $etapas,
            "filter" => $filter,
        ));
    }

    public function newAction(Request $request, $filter)
    {
        $em = $this->getDoctrine()->getManager();
        $bloco = $this->getDoctrine()->getRepository(BlocoController::REPOSITORY_NAME)->find($filter);
        foreach ($request->request->get('etapas') as $etapa)
        {
            $acompanhamentoEtapa = new AcompanhamentoEtapa();
            
            if($etapa['id']){
                $acompanhamentoEtapa = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:AcompanhamentoEtapa')->find($etapa['id']);
                $acompanhamentoEtapa->setValor($etapa['valor']);
                if(isset($etapa['visivel'])){
                	$acompanhamentoEtapa->setVisible(1);
                }else{
                	$acompanhamentoEtapa->setVisible(0);
                }
            }else{
            	
                $acompanhamentoEtapa->setEtapa($this->getDoctrine()->getRepository(EtapaController::REPOSITORY_NAME)->find($etapa['etapa_id']));
                $acompanhamentoEtapa->setValor($etapa['valor']);
                $acompanhamentoEtapa->setPosicao($acompanhamentoEtapa->getEtapa()->getPosition());
                
                $bloco->addEtapa($acompanhamentoEtapa);
            }
        }

        $em->merge($bloco);
        $em->flush();
        
        $return = new ReturnJson();
        $return->setMessage("Etapa alterada com sucesso!");

        return new JsonResponse($return->getJson());
    } 
}