<?php

namespace Tupi\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tupi\AdminBundle\Commom\Util;

/**
 * LinkContent
 *
 * @ORM\Table(name = "content_link")
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * 
 * @ORM\HasLifecycleCallbacks
 */
abstract class LinkContent implements \Serializable
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
     * @ORM\Column(name="title", type="string", length=150)
     */
    private $title;
    
    /**
     * @var string
     *
    * @ORM\Column(name="url", type="string", unique=true, length=200)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function gerarUrl()
    {
        $this->url = Util::toAscii($this->getTitle());
    }

    /**
     * Set id
     *
     * @return LinkContent
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
     * Set title
     *
     * @param string $title
     * @return LinkContent
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set url
     *
     * @param string $url
     * @return url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }
    
    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LinkContent
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return LinkContent
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @see Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array($this->id));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list ($this->id) = unserialize($serialized);
    }
    
    public function __toString()
    {
    	return '[id: ' . $this->id . ', title: ' .  $this->title . ', url: ' . $this->url . ']';
    }
}
