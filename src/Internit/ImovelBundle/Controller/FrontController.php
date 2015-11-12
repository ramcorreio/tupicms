<?php
namespace Internit\ImovelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tupi\ContentBundle\Controller\PageController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Internit\ImovelBundle\Entity\Imovel;
use Tupi\AdminBundle\Controller\BaseController;
use Tupi\ContentBundle\Entity\Page;
use Tupi\ContentBundle\Controller\MenuController;
use Symfony\Component\HttpFoundation\Request;
use Tupi\ContentBundle\Entity\LinkContent;
use Doctrine\Common\Collections\Criteria;

class FrontController extends BaseController
{
	const REPOSITORY_NAME = 'InternitImovelBundle:Imovel';
	
	protected $repositoryName = self::REPOSITORY_NAME;
    
	public function getArrayStatus(){
		return array('imoveis-a-venda' => null ,'lancamentos' => 'Lançamentos', 'em-construcao' => 'Em Construção', 'breves-lancamentos' => 'Breves Lançamentos', 'prontos' => 'Prontos');
	}
	
	public function imoveisAjaxAction()
	{
		$maxResult = 2;
		$status = $this->getRequest()->get('status');
		$page = $this->getRequest()->get('page') * $maxResult;
		$arrayStatus = $this->getArrayStatus();
		
		if($status=="todos"){
			$imoveis = $this->getRepository()->carregamentoSobDemandaImovel($page);
		}
		else{
			$imoveis = $this->getRepository()->carregamentoSobDemandaImovel($page, $status);
		}
	
		return $this->render("InternitSiteBaseBundle:Default:imovel-ajax.html.twig", array(
				'session' => $this->getPage(),
				'imoveis' => $imoveis,
				'status' => $status,
				'arrayStatus' => $arrayStatus,
		));
	}
	
	public function getReverseArrayStatus(){
		$arrayStatus = $this->getArrayStatus();
		$arrayTemporario = array();
		foreach ($arrayStatus as $chave => $valor){
			$arrayTemporario[$valor] = $chave;
		}
		return $arrayTemporario;
	}
	
    public function imoveisAction(LinkContent $page, Request $request)
    { 
        $status = $this->get('request')->attributes->get('page')->getUrl();
        
        $arrayStatus = $this->getArrayStatus();
    	
        $imoveis = $this->getRepository()->findByStatus($arrayStatus[$status]);
        
        $totais = $this->getRepository()->totalImovelByStatus();
        
        foreach ($totais as &$valor)
        {
            $valor['url'] = array_search($valor['status'], $arrayStatus);
        }
        $status = $this->getRespositoryName(ImovelStatusController::REPOSITORY_NAME)->findAllVisible();
        
    	return $this->render("InternitSiteBaseBundle:Default:imoveis.html.twig", array(
    		'session' => $page,
    	    'imoveis' => $imoveis,
    	    'total' => $totais,
    		'status' => $status,
    		'arrayStatus' => $arrayStatus,
    	));
    }    
    
    public function fichaAction(Request $request, $url)
    { 
    	$ficha = $this->getRepository()->findOneBy(array("url" => $url, 'visible' => true));
    	$acompanhamento = $this->getRespositoryName("InternitAcompanhamentoBundle:Acompanhamento")->findOneBy(array('imovel' => $ficha));
    	
    	
    	$videos = array();
    	 
    	if($acompanhamento){    		
    		foreach ($ficha->getVideos() as $video){
    			$id_video = explode("?v=", $video->getVideo() );
    			$videos[] = $id_video[1];
    		}    		
    		
        	$page = $this->getPage();
        	$page->setTitle($ficha->getName());
        	return $this->render("InternitSiteBaseBundle:Default:ficha-do-imovel.html.twig", array(
        			'session' => $page,
        			'ficha' => $ficha,
        			'videos'=> $videos,
        	        'acompanhamento' => $acompanhamento,
        	));
    	}else{
    	    throw $this->createNotFoundException('Imóvel não existe');
    	}    	
    }
    
    public function obrasRealizadasAjaxAction(Request $request)
    {   	
    	$infor = array(
    			'maxResult' => $this->getRequest()->get('max'),
    			'page' => $this->getRequest()->get('page'),
    	);
    	
    	$imoveis = $this->getRepository()->carregamentoSobDemandaObrasRealizadas($infor);
    	
    	return $this->render("InternitSiteBaseBundle:Default:obras-realizadas-ajax.html.twig", array(
    			'session' => $this->getPage(),
    			'imoveis' => $imoveis,
    	));
    }
    
    public function obrasRealizadasAction(LinkContent $page,Request $request)
    {
        $imoveis = $this->getRepository()->findByRealizados();
        return $this->render("InternitSiteBaseBundle:Default:obras-realizadas.html.twig", array(
            'session' => $page,
            'imoveis' => $imoveis,
        ));
    }
    
    public function homeAction(LinkContent $page,Request $request, $path = 'home')
    {
    	
    	$banner = $this->getDoctrine()->getRepository('InternitBannerBundle:Banner')->findBy(array("visible" => 1), array('position'=>Criteria::ASC));
    	$imoveis = $this->getRepository()->destaquesImovel(2);
    	$acompanhamentos = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Acompanhamento')->findBy(array("status" => 1));
    	$arrayStatus = $this->getReverseArrayStatus();
		return $this->render("InternitSiteBaseBundle:Default:index.html.twig", array(
    			'session' => $page,
    			'imoveis' => $imoveis,
		        'acompanhamentos' => $acompanhamentos,
				'banners' => $banner,
				'arrayStatus' => $arrayStatus,
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