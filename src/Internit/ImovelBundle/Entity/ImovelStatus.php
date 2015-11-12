<?php

namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Tupi\AdminBundle\Commom\Util;

/**
 * ImovelStatus
 *
 * @ORM\Table(name="imovel_status")
 * @ORM\Entity(repositoryClass="Internit\ImovelBundle\Entity\ImovelStatusRepository")
 * @ORM\HasLifecycleCallbacks
 */
class ImovelStatus
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
     * @ORM\Column(name="status", type="text", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;    
    
    /**
     * @ORM\OneToMany(targetEntity="Imovel", mappedBy="status")
     */
    private $imoveis;

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
	
	public function __construct()
	{
		$this->imoveis = new ArrayCollection();
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

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	
	public function __toString()
	{
		return $this->getStatus();
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
    
    public function addImovel(Imovel $imovel)
    {
    	$this->imoveis->add($imovel);
    	return $this;
    }
    
    public function removeImovel(Imovel $imovel)
    {
    	$this->imoveis->removeElement($imovel);
    }    
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function gerarUrl()
    {
    	$this->url = Util::toAscii($this->getStatus());
    }
    
	public function getUrl() {
		return $this->url;
	}
	
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}
	
	public function getImoveis() {
		return $this->imoveis;
	}
	
	public function setImoveis($imoveis) {
		$this->imoveis = $imoveis;
		return $this;
	}
	    
}