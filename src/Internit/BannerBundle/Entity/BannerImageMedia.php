<?php

namespace Internit\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tupi\ContentBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * ImageMedia 
 * 
 * @ORM\Table(name = "banner_content_media")
 * @ORM\Entity(repositoryClass="Tupi\ContentBundle\Entity\MediaFileRepository")
 */
class BannerImageMedia extends Media
{
	
	public function __construct()
	{
		parent::__construct();
		$this->thumbs = new ArrayCollection();
	}
	
	/**
	 * @ORM\OneToMany(targetEntity="Thumb", mappedBy="imageMedia", cascade={"all"})
	 */	
	private $thumbs;
	
	/**
	 * Set thumb
	 *
	 * @param string $thumb
	 * @return string
	 */
	public function setThumbs($thumbs)
	{
		$this->thumbs = $thumbs;
	
		return $this;
	}
	
	/**
	 * Get thumb
	 *
	 * @return string
	 */
	public function getThumbs()
	{
		return $this->thumbs;
	}
	
	public function addThumb(Thumb $thumb) {
	    $thumb->setImageMedia($this);
	    $this->thumbs->add($thumb);
	    return $this;
	}
	
	public function removeThumb(Thumb $thumb) {
	    $this->thumbs->removeElement($thumb);
	}
	

	public function getType() {
		 
		return 'Imagem';
	}
	
	public function getThumb($name)
	{
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("name", $name));
        return $this->thumbs->matching($criteria)->first();
	}
	
	public function getExtension()
	{
	    $extension = array (
	        'image/jpg' => 'jpg',
	        'image/jpeg' => 'jpeg',
	        'image/gif' => 'gif',
	        'image/png' => 'png',
	        'image/bmp' => 'bmp'
	    );
	     
	    return $extension[$this->getMimeType()];
	}
}