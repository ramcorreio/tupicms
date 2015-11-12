<?php
namespace Internit\AcompanhamentoBundle\Form;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tupi\AdminBundle\Commom\Base64;
use Tupi\AdminBundle\Types\StatusType;
use Tupi\ContentBundle\Entity\ImageMedia;
use Tupi\ContentBundle\Entity\Base64String;
use Internit\AcompanhamentoBundle\Entity\Thumb;

class UploadImageHelper
{

    private $image;
    
    private $file;
    
    private $crops;
    
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
    
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
    
    public function setCrops(CropCollection $crops)
    {
        $this->crops = $crops;
    
        return $this;
    }
    
    public function getCrops()
    {
        return $this->crops;
    }
    
    public function createThumbs()
    {  
        $this->image->setMimeType($this->file->getMimeType());
        $this->image->setStatus(StatusType::ACTIVE);
        $this->image->setUpdatedAt(new \DateTime());
        $this->generateName();
        
        if(is_null($this->image->getCreatedAt())) {
            $this->image->setCreatedAt($this->image->getUpdatedAt());
        }
        
        foreach ($this->crops as $crop)
        {
            $thumb = new Thumb();
            $thumb->setName($crop->getName());
            $this->resizeImage($crop, $thumb);
            $this->image->addThumb($thumb);
        }
    }
    
    private function resizeImage(Crop $crop, Thumb &$thumb)
    {
        
        $dst_x = $dst_y = $src_x = $src_y = 0;
        list($orig_w, $orig_h) = getimagesize($this->file->getRealPath());
    
        $w = $orig_w;
        $h = $orig_h;
        
        if($crop->getCrop()) {
            $ratio = max($crop->getWidth()/$orig_w, $crop->getHeight()/$orig_h);
            if($orig_w > $orig_h)
                $src_x = ($orig_w - $crop->getWidth() / $ratio) / 2;
            else if($orig_h > $orig_w)
                $src_y = ($orig_h - $crop->getHeight() / $ratio) / 2;
    
            $orig_h = $crop->getHeight() / $ratio;
            $orig_w = $crop->getWidth() / $ratio;
            $h = $crop->getHeight();
            $w = $crop->getWidth();
        }
        else {
        
            //resize only
            # taller
            if ($h > $crop->getHeight()) {
                $w = ($crop->getHeight() / $h) * $w;
                $h = $crop->getHeight();
            }
             
            # wider
            if ($w > $crop->getWidth()) {
                $h = ($crop->getWidth() / $w) * $h;
                $w = $crop->getWidth();
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
        $thumb->setValue(Base64::encodePath($tmpfname));
        unlink($tmpfname);
    }
       
    public function doUpload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return false;
        }

        $bin = new Base64String();
        $bin->setValue(Base64::encodeFile($this->file));
        $this->image->setBin($bin);
         
        return true;
    }

    private function generateName()
    {
        $title = (strrpos( $this->getFile()->getClientOriginalName(), '.') === false) ?  $this->getFile()->getClientOriginalName() : substr( $this->getFile()->getClientOriginalName(), 0,	strrpos( $this->getFile()->getClientOriginalName(), '.'));
        $title .= "_".uniqid();
        
        $this->image->setLabel($title);
        $this->image->setTitle($title);
    }
}
