<?php

namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImovelTag
 *
 * @ORM\Table(name="imovel_tag")
 * @ORM\Entity(repositoryClass="Internit\ImovelBundle\Entity\ImovelRepository")
 */
class ImovelTag
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
     * @ORM\Column(name="tag", type="text", length=100)
     */
    private $tag;

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
     * @var string
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position = 0;    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
	public function getTag() {
		return $this->tag;
	}
	public function setTag($tag) {
		$this->tag = $tag;
		return $this;
	}
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ContactRequest
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
     * @return ContactRequest
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
    
    public function __toString()
    {
    	return $this->tag;
    }
    
	public function getPosition() {
		return $this->position;
	}
	
	public function setPosition($position) {
		$this->position = $position;
		return $this;
	}
	
}