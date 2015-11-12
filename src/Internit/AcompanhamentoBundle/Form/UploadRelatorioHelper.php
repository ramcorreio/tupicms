<?php
namespace Internit\AcompanhamentoBundle\Form;

use Tupi\ContentBundle\Entity\Media;
use Tupi\AdminBundle\Types\StatusType;
use Tupi\AdminBundle\Commom\Base64;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadRelatorioHelper {
	
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
	
}
