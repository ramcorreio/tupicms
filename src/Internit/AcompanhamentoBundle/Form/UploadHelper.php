<?php
namespace Internit\AcompanhamentoBundle\Form;

use Tupi\AdminBundle\Commom\Base64;
use Tupi\AdminBundle\Types\StatusType;
use Tupi\ContentBundle\Entity\ImageMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHelper
{

    /**
     * @var MediaFile
     *
     */
    private $media;
    
    /**
     * File to uploaded
     */
    private $file;
    
    
    /**
     * Set $media
     *
     * @param ImageMedia $media
     * @return UploadHelper
     */
    public function setMedia(ImageMedia $media)
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
    
    public function __construct(ImageMedia $media, $file)
    {
        $this->setMedia($media);
        $this->setFile($file);
        $this->generateName();
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
    public function thumbnail()
    {
        $this->resizeImage(true, 120, 120);
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
    private function resizeImage($crop = false, $max_w = 150, $max_h = 120, $galeriaCrop = false)
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
         
        $tmpfname = tempnam(sys_get_temp_dir(), uniqid()); // good
        switch($this->file->getMimeType()) {
             
            case "image/gif":
                imagegif($image_p, $tmpfname, 100);
                break;
            case "image/png":
                imagepng($image_p, $tmpfname, 9);
                break;
            case "image/jpeg":
            default:
                imagejpeg($image_p, $tmpfname, 100);
                break;
        }
         
        imagedestroy($image_p);
        if(!$galeriaCrop){
        	$this->media->setThumb(Base64::encodePath($tmpfname));
        }else{
        	$this->media->setThumbCrop(Base64::encodePath($tmpfname));
        }
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
         
        return true;
    }
    
    public function resizeCropImage($max_width = 150, $max_height = 120, $crop = false, $galeriaCrop = false)
    {
        $this->resizeImage($crop, $max_width, $max_height, $galeriaCrop);
    }
    
    private function generateName()
    {
        $title = (strrpos( $this->getFile()->getClientOriginalName(), '.') === false) ?  $this->getFile()->getClientOriginalName() : substr( $this->getFile()->getClientOriginalName(), 0,	strrpos( $this->getFile()->getClientOriginalName(), '.'));
        $title .= "_".date('U');
        
        $this->getMedia()->setTitle($title);
        $this->getMedia()->setLabel($title);
        $this->getMedia()->setSummary($title);
    }
}
