<?php
namespace Internit\BannerBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;

use Symfony\Component\Routing\RequestContext;
use Internit\ContactBundle\Entity\ContactPerson;
use Tupi\ContentBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Internit\ContactBundle\Form\UploadHelper;
use Internit\BannerBundle\Form\BannerType;
use Internit\BannerBundle\Entity\Banner;
use Tupi\ContentBundle\Controller\MenuController;
use Symfony\Component\HttpFoundation;
use Internit\BannerBundle\Entity\BannerImageMedia;
use Internit\BannerBundle\Form\UploadImageHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Internit\BannerBundle\Form\Crop;
use Internit\BannerBundle\Form\CropCollection;

class BannerController extends CrudController
{
    const REPOSITORY_NAME = 'InternitBannerBundle:Banner';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitBannerBundle:Banner';
    
    protected $defaultRoute = 'banner_home';
    
    
    protected function createTypedForm($type)
    {
    	return $this->createForm(new BannerType($this->getDoctrine()->getManager()), $type);
    }
    
    protected function initObject($id = null) 
    {        
        $obj = new Banner();
        if(!empty($id)) {
        
            $obj = $this->getRepository()->findOneBy(array('id' => $id));
        }
        return $obj;
    }
    
    protected function removed(ReturnVal $return)
    {
        $return->setMessage("Banner excluído com sucesso!");
    }
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {

        $this->setBanner($form, $obj, $em);
        
        if(is_null($obj->getId())) {
            $lastPosition = array();
            $obj->setCreatedAt(new \DateTime());
            $obj->setUpdatedAt(new \DateTime());
            $count = $this->getRepository()->getCount();
            if($count == 0){
                $lastPosition['position'] = 0;
            }else{
                $lastPosition = $this->getRepository()->lastPosition();
            }
            $obj->setPosition(($lastPosition['position'] + 1));
            $em->persist($obj);
            $return->setMessage('Banner cadastrado com sucesso!');
        }else {
            $obj->setUpdatedAt(new \DateTime());
            $em->merge($obj);
            $return->setMessage('Banner alterado com sucesso!');
        }
    }
    
    
    private function createThumb($file, $cropCollection)
    {
    
        $image = new BannerImageMedia();
    
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
    
    private function setBanner($form, $obj, $em){
        $banner_desktop = $form['imageDesktop']['imagem']->getData();
        $banner_tablet = $form['imageTablet']['imagem']->getData();
        $banner_celular = $form['imageCelular']['imagem']->getData();
        
//         var_dump($banner_celular);
//         var_dump($banner_desktop);
//         var_dump($banner_celular);
        
//         exit;
        
        if(!empty($banner_desktop))
        {
            $cropCollection = new CropCollection();
            $cropCollection->add('admin', new Crop('admin', 200, 200, false));
            $cropCollection->add('bannerDesktop', new Crop('bannerDesktop', 1270, 500));
    
            $image = $this->createThumb($banner_desktop, $cropCollection);
            	
            $em->persist($image);
            	
            $obj->setImageDesktop($image);
        }
        
        if(!empty($banner_tablet))
        {
        	$cropCollection = new CropCollection();
        	$cropCollection->add('admin', new Crop('admin', 200, 200, false));
        	$cropCollection->add('bannerTablet', new Crop('bannerTablet', 980, 500));
        
        	$image = $this->createThumb($banner_tablet, $cropCollection);
        	 
        	$em->persist($image);
        	 
        	$obj->setImageTablet($image);
        }
        
        if(!empty($banner_celular))
        {
        	$cropCollection = new CropCollection();
        	$cropCollection->add('admin', new Crop('admin', 200, 200, false));
        	$cropCollection->add('bannerCelular', new Crop('bannerCelular', 768, 500));
        
        	$image = $this->createThumb($banner_celular, $cropCollection);
        	 
        	$em->persist($image);
        	 
        	$obj->setImageCelular($image);
        }
    }
    
    public function saveBanner(){
    	
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
    
        return new JsonResponse(array("success" => true, "message" => "Banner reordenados com sucesso!"));
    }
}