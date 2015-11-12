<?php
namespace Internit\AcompanhamentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tupi\ContentBundle\Entity\ImageMedia;
use Internit\AcompanhamentoBundle\Form\UploadHelper;
use Symfony\Component\HttpFoundation\Request;
use Internit\AcompanhamentoBundle\Form\UploadRelatorioHelper;
use Internit\AcompanhamentoBundle\Entity\Relatorio;
use Internit\AcompanhamentoBundle\Entity\Bloco;

class RelatorioController extends Controller
{
    public function indexAction($filter)
    {
        $bloco = $this->getDoctrine()->getRepository(BlocoController::REPOSITORY_NAME)->find($filter);
        return $this->render('InternitAcompanhamentoBundle:Relatorio:cadastro.html.twig', array(
            "relatorios" => $bloco->getRelatorios(),
            "filter" => $filter,
        ));
    }

    public function newAction($filter)
    {
        $em = $this->getDoctrine()->getManager();
	    
	    $upload = new UploadRelatorioHelper();
	    $fileInput = $this->getRequest()->files->get('file');
	    
	    $bloco = $this->getDoctrine()->getRepository(BlocoController::REPOSITORY_NAME)->find($filter);
	    
	    $title = (strrpos($fileInput->getClientOriginalName(), '.') === false) ? $fileInput->getClientOriginalName() : substr($fileInput->getClientOriginalName(), 0,	strrpos($fileInput->getClientOriginalName(), '.'));
	    $title .= "_".date('U');
	    
	    $relatorio = new Relatorio();
	    $relatorio->setTitle($title.'.'.$fileInput->guessExtension());
	    $relatorio->setLabel($title);
	    $relatorio->setSummary($title);
	    
	    $upload->setFile($fileInput);
	    $upload->setMedia($relatorio);
	    
	    $upload->doUpload();
	    
	    $bloco->AddRelatorio($relatorio);
	     
	    $em->merge($bloco);
	    $em->flush();
	    
	    return $this->render('InternitAcompanhamentoBundle:Relatorio:relatorio.html.twig', array(
	        "relatorio" => $relatorio,
	        "filter" => $filter,
	    ));
    }
    
    public function editAction(Request $request, $id, $filter)
    {
        $em = $this->getDoctrine()->getManager();
        $relatorio = $this->getDoctrine()->getRepository("InternitAcompanhamentoBundle:Relatorio")->find($id);
        $relatorio->setLabel($request->request->get('legenda'));
        $em->merge($relatorio);
        $em->flush();
        
        $return = new ReturnJson();
        $return->setMessage("Legenda alterada com sucesso!");

        return new JsonResponse($return->getJson());
    }
    
    public function removeAction($id, $filter)
    {
        $em = $this->getDoctrine()->getManager();
        $bloco = $this->getDoctrine()->getRepository(BlocoController::REPOSITORY_NAME)->find($filter);
        $relatorio = $this->getDoctrine()->getRepository("InternitAcompanhamentoBundle:Relatorio")->find($id);
        
        $bloco->removeRelatorio($relatorio);
        $em->remove($relatorio);
        $em->flush();
        
        $return = new ReturnJson();
        $return->setMessage("Relatório deletado com sucesso!");

        return new JsonResponse($return->getJson());
    }
    
}