<?php
namespace Tupi\SecurityBundle\Controller;

use Tupi\SecurityBundle\Form\SettingType;
use Tupi\SecurityBundle\Entity\Setting;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\CrudController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\Security\Acl\Exception\Exception;

class SettingController extends CrudController
{
	const REPOSITORY_NAME = 'TupiSecurityBundle:Setting';
	
	protected $repositoryName = self::REPOSITORY_NAME;
	
	protected $bundleName = 'TupiSecurityBundle:Setting';
	
	protected $defaultRoute = 'tupi_setting_home';
	
	public function createTypedForm($type)
	{
	    return $this->createForm(new SettingType(), $type,  array(
            'attr' => array()
	    ));
	}
	
	protected function initObject($id = null) 
	{    
	   $setting = $this->getRepository()->findOneBy(array('id' => $id));
	    if(empty($setting)) {
	        $setting = new Setting();
	    }
	    
	    return $setting;
	}
	
	protected function removed(ReturnVal $return)
	{
		
	}
	
	protected function save(ReturnVal $return, $id = null, $setting, $form, EntityManager $em)
	{
		if(is_null($setting->getId())) {
			$em->persist($setting);
			$return->setMessage('Definições cadastradas com sucesso!');
		}
		else {
			$em->merge($setting);
			$return->setMessage('Definições alteradas com sucesso!');
		}
    }
    
    public function deleteDirAction()
    {
        $path = $this->get('kernel')->getRootDir()."/cache/";
        $diretorio = dir($path); 
        try{
            while($arquivo = $diretorio->read()){
                if($arquivo!="." && $arquivo!=".."){
                    $this->removeDir($path.$arquivo);
                }
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        
        $diretorio -> close();
        
        echo 'Cache Deletado com sucesso';
        exit();
    }
    
    
    public function removeDir($dir){
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") 
                        $this->removeDir($dir."/".$object); 
                    else 
                        unlink($dir."/".$object);
                }
            }
            reset($objects);
            \rmdir($dir);
        }
    }    


}