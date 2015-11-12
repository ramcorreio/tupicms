<?php
namespace Internit\AcompanhamentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name = "acompanhamento_imovel")
 * @ORM\Entity(repositoryClass="Internit\AcompanhamentoBundle\Entity\AcompanhamentoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Acompanhamento
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
     * @ORM\OneToOne(targetEntity="Internit\ImovelBundle\Entity\Imovel")
     * @ORM\JoinColumn(name="imovel_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $imovel;  
    
    /**
     * @ORM\OneToOne(targetEntity="AcompanhamentoImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Bloco", mappedBy="acompanhamento", cascade={"all"}, orphanRemoval=true)
     **/
    private $blocos;
    
    /**
	 * @var boolean
	 *
	 * @ORM\Column(name="status", type="boolean")
	 */
	private $status = false;
	
	/**
	 *
	 * @var string @ORM\Column(name="position", type="integer")
	 */
	private $position = 0;
    
	/**
	 *
	 * @var string @ORM\Column(name="percentual", type="integer")
	 */
	private $percentual = 0;	
	
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
        $this->blocos = new ArrayCollection();    
    }
    

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    public function getPosition() {
    	
    	return $this->position;
    }
    
    public function setPosition($position)
    {
    	$this->position = $position;
    	return $this;
    }

    public function getPercentual()
    {
    	return $this->percentual;
    }
    
    public function setPercentual($percentual)
    {
    	$this->percentual = $percentual;
    	return $this;
    }
    
    public function getImovel()
    {
        return $this->imovel;
    }

    public function setImovel($imovel)
    {
        $this->imovel = $imovel;
        return $this;
    }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    
    public function getBlocos()
    {
        return $this->blocos;
    }
    
    public function setBlocos($blocos)
    {
        $this->blocos = $blocos;
        return $this;
    }
    
    public function addBloco(Bloco $bloco) {
        $bloco->setAcompanhamento($this);
        $this->blocos->add($bloco);
        return $this;
    }
    
    public function removeBloco(Bloco $bloco) {
        $this->blocos->removeElement($bloco);
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(){
        $this->updatedAt = new \DateTime();
    }

	
}