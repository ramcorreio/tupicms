<?php
namespace Internit\AcompanhamentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Tupi\ContentBundle\Entity\ImageMedia;
use Doctrine\ORM\NoResultException;

/**
 *
 * @ORM\Table(name = "acompanhamento_galeria")
 * @ORM\Entity(repositoryClass="Internit\AcompanhamentoBundle\Entity\GaleriaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Galeria
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
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bloco", inversedBy="galerias")
     * @ORM\JoinColumn(name="bloco_id", referencedColumnName="id")
     **/
    private $bloco;    
    
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
	 * @var images
	 * 
     * @ORM\ManyToMany(targetEntity="Tupi\ContentBundle\Entity\ImageMedia", fetch="EXTRA_LAZY" , cascade={"persist"})
     * @ORM\JoinTable(name="acompanhamento_galeria_imagem",
     *     joinColumns={@ORM\JoinColumn(name="galeria_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mediafile_id", referencedColumnName="id")}
     * )
	 */
	private $images;
	
	public function __construct()
	{
		$this->images = new ArrayCollection();
	}
	
    /**
     * Set id
     *
     * @return ContactPerson
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


    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
    

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ContactPerson
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
     * @return ContactPerson
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
	
	public function setImages($images)
	{
		$this->images = $images;
	
		return $this;
	}
	
	public function getImages()
	{
		return $this->images;
	}
    
	
	public function addImage(ImageMedia $image) {
	    $this->images->add($image);
	    return $this;
	}
	
	public function removeImage(ImageMedia $image) {
	    $this->images->removeElement($image);
	}

    /**
     * @ORM\PrePersist
     */
    public function generatePosition(LifecycleEventArgs $args)
    {
        $posicao = 0;
        try {
            $value = $args->getEntityManager()
                ->getRepository('InternitAcompanhamentoBundle:Galeria')
                ->lastPosition();
            $posicao = $value['position'] + 1;
        } catch(NoResultException $e){
            $posicao = 1;
        }
        $this->position = $posicao;
    }

    public function getBloco()
    {
        return $this->bloco;
    }

    public function setBloco($bloco)
    {
        $this->bloco = $bloco;
        return $this;
    }
}