<?php

namespace Tupi\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageMedia 
 * 
 * @ORM\Table(name = "content_media")
 * @ORM\Entity(repositoryClass="Tupi\ContentBundle\Entity\MediaFileRepository")
 * @ORM\EntityListeners({"Tupi\ContentBundle\EventListener\ImageMediaListener"})
 */
class ImageMedia extends Media
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="thumb", type="text", nullable=true)
	 */
	private $thumb;
	
	
	/**
	 * Set thumb
	 *
	 * @param string $thumb
	 * @return string
	 */
	public function setThumb($thumb)
	{
		$this->thumb = $thumb;
	
		return $this;
	}
	
	/**
	 * Get thumb
	 *
	 * @return string
	 */
	public function getThumb()
	{
		return $this->thumb;
	}
	

	public function getType() {
		 
		return 'Imagem';
	}
}