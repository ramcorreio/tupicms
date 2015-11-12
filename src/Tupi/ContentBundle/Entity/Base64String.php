<?php
namespace Tupi\ContentBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Base64String
 *
 * @ORM\Table(name = "content_media_string")
 * @ORM\Entity(repositoryClass="Tupi\ContentBundle\Entity\MediaFileRepository")
 * 
 */
class Base64String
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="text")
	 */
	private $value;
	
	/**
	 * Get id
	 *
	 * @return Base64String
	 */
	public function setId($id)
	{
		$this->id = $id;
		 
		return $this;
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Set $value
	 *
	 * @return Base64String
	 */
	public function setValue($value)
	{
		$this->value = $value;
	
		return $this;
	}
	
	
	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getValue() 
	{
		return $this->value;
	}
}