<?php
namespace Internit\OferecaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class ExportController extends Controller
{
	
    public function generateCsvAction()
    {
    	//pegando a query com a função contectFile
    	$data = $this->contentFile();
    	//nome do arquivo
    	$filename = "contatos-site_".date("d-m-Y").".csv";
    	
    	$response = $this->render('InternitOferecaBundle:Export:index.html.twig', array('data' => $data));
    	$response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    	$response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');
    	
    	return $response;
    }
    
    private function contentFile()
    {    	
    	return $contacts = $this->getDoctrine()
    							->getRepository('InternitOferecaBundle:Ofereca')
    							->getContacts();
    }
}
