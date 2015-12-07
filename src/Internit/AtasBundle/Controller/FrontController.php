<?php

namespace Internit\AtasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tupi\ContentBundle\Controller\PageController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Internit\AtasBundle\Entity\Atas;
use Tupi\ContentBundle\Entity\LinkContent;
use Symfony\Component\HttpFoundation\Request;
use Tupi\AdminBundle\Commom\Paginator;



class FrontController extends Controller
{
	public function atasAction(Request $request, LinkContent $page){
		$tag = $request->get('tag');
	 	
		if($tag){
			$paginator = $this->getDoctrine()->getRepository('InternitAtasBundle:Atas')->findByTitulo($tag);
		}
		else{
			$paginator = $this->getDoctrine()->getRepository('InternitAtasBundle:Atas')->findAll();
		}
				
		/*$limite_por_pagina = 4;
		$paginator = new Paginator($this->getDoctrine()->getRepository('InternitAtasBundle:Atas')->listItens());
		$paginator->init($this->getRequest()->get('pager', 1), $this->getRequest()->getSession()->get('lines', $limite_por_pagina));
		$totalPaginas = $paginator->getTotalPages();*/
						
		return $this->render("InternitSiteBaseBundle:Default:atas-vigentes.html.twig", array(
				'session' => $page,
				'paginator' => $paginator
		));
	}
}