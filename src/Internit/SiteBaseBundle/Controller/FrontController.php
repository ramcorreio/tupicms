<?php

namespace Internit\SiteBaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tupi\ContentBundle\Controller\PageController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Tupi\ContentBundle\Entity\Page;

class FrontController extends Controller
{
    public function homeAction()
    {
    	$acompanhamentos = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Acompanhamento')->findBy(array("status" => 1));
    	return  $this->render('InternitSiteBaseBundle:Default:index.html.twig', array(
    			'session' => new Page(),
    			'acompanhamentos' => $acompanhamentos,
    	));
    }
}
