<?php

namespace Tupi\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media
 * 
 * @ORM\MappedSuperclass
 */
abstract class Media extends BaseContent
{
	
	/**
	 * @var string
	 *
	 * @ORM\OneToOne(targetEntity="Base64String", cascade={"all"}, orphanRemoval=true)
	 * @ORM\JoinColumn(name="bin_id", referencedColumnName="id")
	 */
	private $bin;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="label")
	 */
	private $label;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="mime_type", type="text")
	 */
	private $mimeType;
	
	/**
	 * @var status
	 *
	 * @ORM\Column(name="status", type="status")
	 */
	private $status;
	
	
	protected function __construct()
	{
		$this->bin = new Base64String();
	}
	
	/**
	 * Set bin
	 *
	 * @param Base64String $bin
	 * @return Media
	 */
	public function setBin($bin)
	{
		$this->bin = $bin;
	
		return $this;
	}
	
	/**
	 * Get bin
	 *
	 * @return Base64String
	 */
	public function getBin()
	{
		return $this->bin;
	}
	
	/**
	 * Set label
	 *
	 * @param string $label
	 * @return MediaFile
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	
		return $this;
	}
	
	/**
	 * Get label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}
	
	/**
	 * Set mimeType
	 *
	 * @param string $label
	 * @return Arquivo
	 */
	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;
	
		return $this;
	}
	
	/**
	 * Get mimeType
	 *
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->mimeType;
	}
	
	/**
	 * Set status
	 *
	 * @param status $status
	 * @return Media
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	
		return $this;
	}
	
	/**
	 * Get status
	 *
	 * @return status
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	
    /**
     * Get type
     *
     * @return string
     */
    public abstract function getType();
}