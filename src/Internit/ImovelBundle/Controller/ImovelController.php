<?php

namespace Internit\ImovelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;

use Tupi\AdminBundle\Controller\BaseController;
use Tupi\AdminBundle\Controller\CrudController;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;

use Internit\ImovelBundle\Form\ImovelType;
use Internit\ImovelBundle\Entity\Imovel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tupi\ContentBundle\Entity\ImageMedia;
use Symfony\Component\HttpFoundation\Response;
use Internit\ImovelBundle\Entity\Image;
use Tupi\AdminBundle\Types\StatusType;
use Internit\ImovelBundle\Entity\ImovelImageMedia;
use Internit\ImovelBundle\Form\Crop;
use Internit\ImovelBundle\Form\CropCollection;
use Internit\ImovelBundle\Form\UploadImageHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Internit\ImovelBundle\Entity\ImovelArquivo;
use Internit\AcompanhamentoBundle\Form\UploadRelatorioHelper;
use Tupi\ContentBundle\Controller\MenuController;

class ImovelController extends CrudController
{
	const REPOSITORY_NAME = 'InternitImovelBundle:Imovel';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'InternitImovelBundle:Imovel';
	
	protected $defaultRoute = 'imovel_home';
	
	protected function createTypedForm($type)
	{	
		return $this->createForm(new ImovelType($this->getDoctrine()->getManager()), $type);
	}
	
	protected function initObject($id = null)
	{
		$obj = new Imovel();
		if(!empty($id)) {
	
			$obj = $this->getRepository()->findOneBy(array('id' => $id));
		}
		return $obj;
	}   
	
	protected function removed(ReturnVal $return)
	{
		$return->setMessage("Imóvel excluído com sucesso!");
	}
	
	private function setImages($obj){
	    $images = new ArrayCollection();
	    
	    foreach ($obj->getImages() as $image)
	    {
	        if(is_null($image->getBin()->getId()))
	        {
	            $label = $image->getLabel();
	            $position = $image->getPosition();
	            $image = $this->getDoctrine()->getManager()->getRepository('InternitImovelBundle:Image')->findOneBy(array('id' => $image->getId()));
	            $image->setLabel($label);
	            $image->setImovel($obj);
	            $image->setPosition($position);
	            $images->add($image);
	        }
	    }
	    
	    $obj->setImages($images);
	}
	
	private function createThumb($file, $cropCollection)
	{

	    $image = new ImovelImageMedia();
	    	
	    $upload = new UploadImageHelper();
	    $upload->setFile($file);
	    $upload->setCrops($cropCollection);
	    $upload->setImage($image);
	    	
	    if($upload->doUpload() && $upload->isImage())
	    {
	        $upload->createThumbs();
	        $image = $upload->getImage();
	    }
	    
	    return $image;
	}
	
	
	private function setImageDestaque($form, $obj, $em){
	
	    $file = $form['imagemDestaque']['imagem']->getData();
	    if(!empty($file))
	    {
    	    $cropCollection = new CropCollection();
    	    $cropCollection->add('admin', new Crop('admin', 200, 200, false));
    	    $cropCollection->add('destaque', new Crop('destaque', 585, 409));
    	    $cropCollection->add('destaque_home',  new Crop('destaque_home', 585, 409));
    	    $cropCollection->add('acompanhamento_todos',  new Crop('acompanhamento_todos', 380, 338));
    	    $cropCollection->add('acompanhamento_ficha',  new Crop('acompanhamento_ficha', 630, 441));

    	   
    	    $image = $this->createThumb($file, $cropCollection);
    	    
    	    $em->persist($image);
    	    
    	    $obj->setImagemDestaque($image);
	    }
	}
	
	private function setLogo($form, $obj, $em){
	
	    $file = $form['logo']['imagem']->getData();
	     
	    if(!empty($file))
	    {
	        $cropCollection = new CropCollection();
	        $cropCollection->add('admin', new Crop('admin', 200, 200, false));
	        $cropCollection->add('logo_imovel', new Crop('logo_imovel', 138, 88, false));
	        $cropCollection->add('logo_acompanhamento_todos', new Crop('logo_acompanhamento_todos', 78, 45, false));

	        	
	        $image = $this->createThumb($file, $cropCollection);
	        	
	        $em->persist($image);
	        	
	        $obj->setLogo($image);
	    }
	}
	
	private function setBanner($form, $obj, $em){
	
	    $file = $form['banner']['imagem']->getData();
	
	    if(!empty($file))
	    {
	        $cropCollection = new CropCollection();
	        $cropCollection->add('admin', new Crop('admin', 200, 200, false));
	        $cropCollection->add('banner_ficha', new Crop('banner_ficha', 1256, 457));
	
	        $image = $this->createThumb($file, $cropCollection);
	
	        $em->persist($image);
	
	        $obj->setBanner($image);
	    }
	}
	
	private function setArquivo($form, $obj){
	
        $file = $form['arquivo']['file']->getData();
    	
    	if(!empty($file))
    	{
	    	$arquivo = new ImovelArquivo();
	    	$arquivo->setTitle("documento_".date("U").".".$file->guessExtension());
	    	$arquivo->setLabel("documento");
			
	    	$upload = new UploadRelatorioHelper();
	    	$upload->setMedia($arquivo);
	    	$upload->setFile($file);
	    	$upload->doUpload();
	    	$this->getDoctrine()->getManager()->persist($upload->getMedia());
	    	$obj->setArquivo($upload->getMedia());
    	}
    	elseif($obj->getArquivo()->getTitle()==null){
    		$obj->setArquivo(null);
    	}
	}
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {    
        $this->setImages($obj);	
        $this->setImageDestaque($form, $obj, $em);
        $this->setLogo($form, $obj, $em);
        $this->setBanner($form, $obj, $em);
        $this->setArquivo($form, $obj);
        
        if(is_null($obj->getId())) {
        	$obj->setCreatedAt(new \DateTime());
        	$obj->setUpdatedAt(new \DateTime());
            $em->persist($obj);
            $return->setMessage('Imóvel cadastrado com sucesso!');
        }else {
        	$obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Imóvel alterado com sucesso!');
        }
        $this->refreshMenu($em);
    }
    
    protected function refreshMenu(EntityManager $em)
    {
        $em->flush();
        
        $imovelByStatus = $this->getRepository()->totalImovelByStatusMenu();
        $arrayStatus = array('lancamentos' => 'Lançamento', 'em-construcao' => 'Em Construção', 'breves-lancamentos' => 'Breve Lançamento', 'prontos' => 'Pronto');
        
        foreach ($imovelByStatus as $status)
        {
            $url = array_search($status['status'], $arrayStatus);

            if($url){
                $menu = $this->getRespositoryName(MenuController::REPOSITORY_NAME)->findOneBy(array("url" => $url));
                $menu->setActive($status['total'] > 0 ? true : false);
                $em->merge($menu);
            }
        }
    }
    
    public function cityAction(Request $request)
    {
    	$state_id = $request->query->get('state_id');
    	$estados = $this->getRespositoryName('InternitImovelBundle:State')->find($state_id);
    	
    	$json = array();
    	
    	foreach ($estados->getCities() as $cidade)
    	{
    		$json[$cidade->getId()] = $cidade->getName();
    	}
    	
    	$response = new JsonResponse();
		$response->setContent(json_encode($json));		
		return $response;
    }
    
	public function uploadAction()
	{
	    $cropCollection = new CropCollection();
	    $cropCollection->add('admin', new Crop('admin', 200, 200));
	    $cropCollection->add('interna', new Crop('interna', 267, 241));
	    $cropCollection->add('galeria_imovel', new Crop('galeria_imovel', 1260, 474));
	    $cropCollection->add('galeria_baixo_imovel', new Crop('galeria_baixo_imovel', 152, 136));
	    
	    $request = $this->getRequest();
	    
	    $image = new Image();
	    $image->setArea($request->get('area'));
	    
		$em = $this->getDoctrine()->getManager();
		
		$upload = new UploadImageHelper();
		$upload->setFile($request->files->get('file'));
		$upload->setCrops($cropCollection);
		$upload->setImage($image);		

		if($upload->doUpload() && $upload->isImage())
		{
			$upload->createThumbs();
			$image = $upload->getImage();
		}
		
		$em->persist($image);
		$em->flush();
		
		$return = array("jsonrpc" => "2.0", "result" => null, "id" => $image->getId() , "image" => "data:".$image->getMimeType().";base64,".$image->getThumb('admin')->getValue());
		return new JsonResponse($return);
	}
    
    public function documentoAction($url)
    {
    	$response = new Response();
    	if ($response->isNotModified($this->getRequest())) {
    		return $response;
    	}else{
    		$arquivo = $this->getDoctrine()->getRepository('InternitImovelBundle:ImovelArquivo')->findOneBy(array('url' => $url));
    		$base64 = $arquivo->getBin()->getValue();
    
    		
    		$response = new Response(base64_decode($base64), 200);
    		$response->headers->set('Content-Type', $arquivo->getMimeType());
    		$response->setLastModified($arquivo->getUpdatedAt());

    		return $response;
    	}
    }
    
    public function sortAction()
    {
    	$position = 1;
    
    	$em = $this->getDoctrine()->getManager();
    	   	
    	foreach ($this->getRequest()->get('data') as $id)
    	{
    		$obj = $this->getRepository()->findOneBy(array('id' => $id));
    		$obj->setPosition($position++);

    		$em->merge($obj);
    	}
    	
    	$em->flush();
    
    	return new JsonResponse(array("success" => true, "message" => "Imóveis reordenados com sucesso!"));
    }    
}