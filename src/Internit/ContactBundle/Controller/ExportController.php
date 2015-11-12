<?php

namespace Internit\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class ExportController extends Controller
{
	
    public function generateCsvAction($path)
    {
    	
		if($path){
			
		switch ($path){
			case "contact" : $data = $this->contentFile()->getContacts();break;
			case "person" : $data = $this->contentFile()->getContacts(false);break;
			default : $data = null;
		}
		$filename = $path."_".date("d-m-Y").".csv";
		$response = $this->render('InternitContactBundle:Export:'.$path.'.html.twig', array('data' => $data));
		$response->headers->set('Content-Type', 'text/csv; charset=utf-8');
		$response->headers->set('Content-Disposition', 'attachment; filename="'.$filename.'"');
		return $response;
		
		}
    }
    
    private function contentFile()
    {    	
    	return $contacts = $this->getDoctrine()
    							->getRepository('InternitContactBundle:ContactPerson');
    }
}
