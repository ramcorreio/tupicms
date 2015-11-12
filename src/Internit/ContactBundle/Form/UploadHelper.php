<?php
namespace Internit\ContactBundle\Form;

use Tupi\ContentBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tupi\AdminBundle\Types\StatusType;
use Tupi\ContentBundle\Entity\Base64String;
use Tupi\AdminBundle\Commom\Base64;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;


class UploadHelper {
	
    protected $container;
    
	/**
	 * @var MediaFile
	 * 
	 */
	private $media;
	
	/**
	 * File to uploaded
	 */
	private $file;
	
	public function __construct($container)
	{
	    $this->container = $container;
	}
	
	
	/**
     * Set $media
     *
     * @param ImageMedia $media
     * @return UploadHelper
     */
    public function setMedia(Media $media)
    {
    	$this->media = $media;
    
    	return $this;
    }
    
    /**
     * Get $media
     *
     * @return $media
     */
    public function getMedia()
    {
    	return $this->media;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
    }
    
    /**
     * Returns if file is image
     *
     * @return boolean
     */
    public function isImage()
    {
    	if($this->file == null)
    		return false;
    	
    	switch($this->file->getMimeType()) {
    	
    		case 'image/gif':
    		case 'image/png':
    		case 'image/jpeg':
    			return true;
    		default:
    			return false;
    			
    	}
    }
    
    /**
     * Generate thumb image square
     */
    public function thumbnail($max_width = 196, $max_height = 203)
    {
    	$this->resizeImage(true, $max_width, $max_height);
    }
    
    public function resizeDefaultImage($max_width = 150, $max_height = 120) 
    {
    	$this->resizeImage(false, $max_width, $max_height);
    }
    
    /**
     * Resize an image and keep the proportions
     * 
     * @param boolean $crop
     * @param integer $max_w
     * @param integer $max_h
     */
    private function resizeImage($crop = false, $max_w = 150, $max_h = 120, $base64 = true)
    {
    	$dst_x = $dst_y = $src_x = $src_y = 0;
    	list($orig_w, $orig_h) = getimagesize($this->file->getRealPath());
    
    	$w = $orig_w;
    	$h = $orig_h;
    	
    	//crop image
    	if($crop === true) {
    		$ratio = max($max_w/$orig_w, $max_h/$orig_h);
    		if($orig_w > $orig_h)
    			$src_x = ($orig_w - $max_w / $ratio) / 2;
    		else if($orig_h > $orig_w)
    			$src_y = ($orig_h - $max_h / $ratio) / 2;
    		
    		$orig_h = $max_h / $ratio;
    		$orig_w = $max_w / $ratio;
    		$h = $max_h;
    		$w = $max_w;
    	}
    	else {
    		
    		//resize only
    		# taller
    		if ($h > $max_h) {
    			$w = ($max_h / $h) * $w;
    			$h = $max_h;
    		}
    		 
    		# wider
    		if ($w > $max_w) {
    			$h = ($max_w / $w) * $h;
    			$w = $max_w;
    		}
    	}
    	
    	$image_p = imagecreatetruecolor($w, $h);
    	
    	switch($this->getFile()->getMimeType()) {
    		
    		case "image/gif":
    			$image = imagecreatefromgif($this->file->getRealPath());
    			break;
    		case "image/png":
    			$image = imagecreatefrompng($this->file->getRealPath());
    			break;
    		case "image/jpeg":
    		default:
    			$image = imagecreatefromjpeg($this->file->getRealPath());
    			break;
    	}
    	
    	imagecopyresampled($image_p, $image, $dst_x, $dst_y, $src_x, $src_y, $w, $h, $orig_w, $orig_h);
    	
    	$tmpfname = tempnam(sys_get_temp_dir(), uniqid()); 
    	switch($this->file->getMimeType()) {
    	
    		case "image/gif":
    			imagegif($image_p, $tmpfname);
    			break;
    		case "image/png":
    			imagepng($image_p, $tmpfname);
    			break;
    		case "image/jpeg":
    		default:
    			imagejpeg($image_p, $tmpfname);
    			break;
    	}
    	
    	imagedestroy($image_p);
    	
    	if($base64)
    	{
    	   $this->media->setThumb(Base64::encodePath($tmpfname));
    	   unlink($tmpfname);
    	}else{
    	    return $tmpfname;
    	}
    }	
    
    private function resizeMark($imagePath, $max_h = 120)
    {
        $dst_x = $dst_y = $src_x = $src_y = 0;
        list($orig_w, $orig_h) = getimagesize($imagePath);
    
        $w = $orig_w;
        $h = $orig_h;
         
        if ($h > $max_h) {
            $w = ($max_h / $h) * $w;
            $h = $max_h;
        }    
         
        $image_p = imagecreatetruecolor($w, $h);
        $image = imagecreatefrompng($imagePath);
        imageAlphaBlending($image, false);
        imageSaveAlpha($image, true);
        
        imagecopyresampled($image_p, $image, $dst_x, $dst_y, $src_x, $src_y, $w, $h, $orig_w, $orig_h);
         
        $tmpfname = tempnam(sys_get_temp_dir(), uniqid());
        imagepng($image_p, $tmpfname);
        
        return $tmpfname;
    }
    
    public function widImage($mark, $opacity = 100)
    {   
        $image_w = $image_h = 0;
                       
        list($orig_w, $orig_h) = getimagesize($this->file->getRealPath());
        
        $image_w = ($orig_w > $orig_h) ? 910 : 440;
        $image_h = ($image_w / $orig_w) * $orig_h;
        
        $image = $this->resizeImage(false, $image_w, $image_h, false);        
        $markPath = $this->resizeMark($mark, $image_h * 0.70);
        
        $mark = imagecreatefrompng($markPath);
        imageAlphaBlending($mark, false);
        imageSaveAlpha($mark, true);
        
        list($mark_w, $mark_h) = getimagesize($markPath);
        
        switch($this->getFile()->getMimeType()) {
            case "image/gif":
                $image = imagecreatefromgif($image);
                break;
            case "image/png":
                $image = imagecreatefrompng($image);
                break;
            case "image/jpeg":
            default:
                $image = imagecreatefromjpeg($image);
                break;
        }
        
        imagecopymerge($image, $mark, $image_w - $mark_w, 0, 0, 0, $mark_w, $mark_h, $opacity);
        $tmpfname = tempnam(sys_get_temp_dir(), uniqid());
        
        switch($this->file->getMimeType()) {
             
            case "image/gif":
                imagegif($image, $tmpfname);
                break;
            case "image/png":
                imagepng($image, $tmpfname);
                break;
            case "image/jpeg":
            default:
                imagejpeg($image, $tmpfname);
                break;
        }
         
        imagedestroy($image);
        $this->media->setThumb(Base64::encodePath($tmpfname));
        unlink($tmpfname);
    }
  
    public function doUpload()
    {
    	// the file property can be empty if the field is not required
    	if (null === $this->file) {
    		return false;
    	}
    	
    	$this->media->setMimeType($this->file->getMimeType());
    	$this->media->setStatus(StatusType::ACTIVE);
    	$this->media->setUpdatedAt(new \DateTime());
    	if(is_null($this->media->getCreatedAt())) {
    		$this->media->setCreatedAt($this->media->getUpdatedAt());
    	}
    	
    	$this->media->getBin()->setValue(Base64::encodeFile($this->file));
    	
    	// use the original file name here but you should
    	// sanitize it at least to avoid any security issues
    
    	// move takes the target directory and then the
    	// target filename to move to
    	//$tempDir = sys_get_temp_dir();
    	//$temp = tmpfile();
    	//$this->file = $this->file->move($tempDir, $this->file->getClientOriginalName());
    
    	// set the path property to the filename where you've saved the file
    	//$this->path = $this->file->getName();
    
    	// clean up the file property as you won't need it anymore
    	//$this->file = null;
    	return true;
    }
	
}
