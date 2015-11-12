<?php
namespace Internit\SiteBaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Tupi\ContentBundle\Controller\PageController;
use Tupi\ContentBundle\Types\PageStatusType;
use Internit\AcompanhamentoBundle\Entity\Relatorio;

class MediaController extends \Tupi\AdminBundle\Controller\BaseController
{	
    public function imageAction($size, $url, $ext)
    {
        $response = new Response();
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }else{
            $image = $this->getDoctrine()->getRepository('InternitImovelBundle:ImovelImageMedia')->findOneBy(array('url' => $url));
            $base64 = "";
    
            if($size != 'original')
                $base64 = $image->getThumb($size)->getValue();
            else
                $base64 = $image->getBin()->getValue();
    
            $response = new Response(base64_decode($base64), 200);
            $response->headers->set('Content-Type', $image->getMimeType());
            $response->setLastModified($image->getUpdatedAt());
            return $response;
        }
    }
    
    public function imageAcompanhamentoAction($id, $thumb)
    {
        $response = new Response();
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }else{
            $image = $this->getDoctrine()->getRepository('TupiContentBundle:ImageMedia')->findOneBy(array('id' => $id));
            $base64 = "";
              
            if($thumb == "crop"){
            	$base64 = $image->getThumbCrop();
            }elseif($thumb){
            	$base64 = $image->getThumb();
            }else{
            	$base64 = $image->getBin()->getValue();
            }
    
            $response = new Response(base64_decode($base64), 200);
            $response->headers->set('Content-Type', $image->getMimeType());
            $response->setLastModified($image->getUpdatedAt());
            return $response;
        }
    }
    

    public function documentoAction($url)
    {
        $response = new Response();
        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }else{
            $arquivo = $this->getDoctrine()->getRepository('InternitAcompanhamentoBundle:Relatorio')->findOneBy(array('url' => $url));
    
            $base64 = $arquivo->getBin()->getValue();
    
    		$response = new Response(base64_decode($base64), 200);
    		$response->headers->set('Content-Type', $arquivo->getMimeType());
    		$response->setLastModified($arquivo->getUpdatedAt());
    		
            return $response;
        }
    }
}