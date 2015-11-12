<?php

namespace Internit\AcompanhamentoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tupi\ContentBundle\Controller\PageController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Internit\AcompanhamentoBundle\Entity\Acompanhamento;
use Tupi\ContentBundle\Entity\Page;
use Tupi\AdminBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tupi\ContentBundle\Controller\MenuController;
use Tupi\ContentBundle\Entity\LinkContent;
use Internit\ImovelBundle\Entity\Imovel;

class FrontController extends BaseController
{
    const REPOSITORY_NAME = 'InternitAcompanhamentoBundle:Acompanhamento';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    public function portalAction(Request $request, LinkContent $page){
    	return $this->render("InternitSiteBaseBundle:Default:portal-do-cliente.html.twig", array(
    			"session" => $page,
    	));
    }
    
    public function acompanhamentosAjaxAction()
    {
    	$maxResult = 3;
     	$page = $this->getRequest()->get('page') * $maxResult;
        $acompanhamentos = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Acompanhamento')->carregamentoSobDemandaAcompanhamentos($page);
		

        return  $this->render('InternitSiteBaseBundle:Default:acompanhamento-ajax.html.twig', array(
        		'session' => $this->getPage(),
        		'acompanhamentos' => $acompanhamentos,
        )); 
    }
    
    
    public function readAction()
    {
    	$galerias = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Galeria')->findAll();
    	$etapas = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Etapa')->findAll();
    	if(($galerias == null) OR ($etapas == null))
    	{
    		return  $this->render('InternitSiteBaseBundle:Default:acompanhe.html.twig', array('etapas' => null, 'galerias' => null));
    	} else {
    	
    		return  $this->render('InternitSiteBaseBundle:Default:acompanhe.html.twig', array('etapas' => $etapas, 'galerias' => $galerias));		
    	}
    }
    
    public function acompanhamentosAction($page)
    {
        $acompanhamentos = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Acompanhamento')->findBy(array("status" => 1));
//         foreach ($acompanhamentos as $acompanhamento)
//         {
//         	foreach ($acompanhamento->getBlocos() as $bloco)
//         	{
//         		foreach ($bloco->getEtapas() as $etapa)
//         		{
//         			var_dump($bloco->getBloco() . " - " .$etapa->getEtapa()->getName() . " - " . $etapa->getValor());
//         		}
//         	}
//         }
//         exit();
		$porcentagens = array();
		$i = 0;
		foreach($acompanhamentos as $acompanhamento){
	        $totalValor = 0;
	        $contador = 0;
	        $total = 0;
	        
	        foreach ($acompanhamento->getBlocos() as $bloco)
	        {
	        	foreach ($bloco->getEtapas() as $etapa)
	        	{
	        		//var_dump($bloco->getBloco() . " - " .$etapa->getEtapa()->getName() . " - " . $etapa->getValor());
	        		$totalValor += $etapa->getValor();
	        		$contador++;
	        	}
	        }
	        if($contador > 0){
	        	$total = ($totalValor/$contador);
	        }
	        $porcentagens[$i++] = round($total, 0);
		}

        return  $this->render('InternitSiteBaseBundle:Default:acompanhamento.html.twig', array(
        		'session' => $page,
        		'acompanhamentos' => $acompanhamentos,
        		'porcentagens' => $porcentagens,
        )); 
    }
    
    public function fichaAction($url)
    {
        $acompanhamento = $this->getRepository()->findByImovelUrl($url);
        $acompanhamentos = $this->getRepository()->findBy(array("status" => true));
        $page = $this->getPage();
        $page->setTitle($acompanhamento->getImovel()->getName());
        
        return $this->render("InternitSiteBaseBundle:Default:ficha-acompanhamento.html.twig", array(
            'session' => $page,
            'acompanhamento' => $acompanhamento,
            'acompanhamentos' => $acompanhamentos,
        ));
    }
    
    public function blocoAction($id)
    {
        $bloco = $this->getRespositoryName('InternitAcompanhamentoBundle:Bloco')->find($id);
        
        return $this->render("InternitAcompanhamentoBundle:Front:bloco.html.twig", array(
            'bloco' => $bloco,
        ));
    }
    
    public function galeriaAction(Request $request)
    {
        $id = $request->get("id");
        $galeria = $this->getRespositoryName('InternitAcompanhamentoBundle:Galeria')->find($id);
    
        return $this->render("InternitAcompanhamentoBundle:Front:galeria.html.twig", array(
            'galeria' => $galeria,
        ));
    }
    
    private function getPage()
    {
        $page = $this->get('request')->attributes->get('page');
    
        if(is_null($page)){
            return new Page();
        }
    
        return $this->getDoctrine()->getRepository(MenuController::REPOSITORY_NAME)->getMenuFromUrl($page->getUrl());
    }
}
