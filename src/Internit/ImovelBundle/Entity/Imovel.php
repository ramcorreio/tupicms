<?php
namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Tupi\AdminBundle\Commom\Util;

/**
 * Imovel
 *
 * @ORM\Table(name = "imovel")
 * @ORM\Entity(repositoryClass="Internit\ImovelBundle\Entity\ImovelRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Imovel
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer")
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string @ORM\Column(name="name", type="string", length=150)
     */
    private $name;
    
    /**
     *
     * @var string @ORM\Column(name="name_featured", type="string", length=150)
     */
    private $nameFeatured;

    /**
     *
     * @var string @ORM\Column(name="position", type="integer")
     */
    private $position = 0;

    /**
     *
     * @var string @ORM\Column(name="url", type="string")
     */
    private $url;

    /**
     *
     * @var string @ORM\Column(name="resume", type="string", length=255, nullable=true)
     */
    private $resume;

    /**
     * @ORM\OneToOne(targetEntity="Address", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $address;

    /**
     *
     * @var string @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     *
     * @var string @ORM\Column(name="promo", type="text", nullable=true)
     */
    private $promo;

    /**
     * @ORM\ManyToOne(targetEntity="ImovelStatus", inversedBy="imoveis")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $status;

    /**
     *
     * @var boolean @ORM\Column(name="destaque", type="boolean")
     */
    private $destaque = false;

    /**
     *
     * @var string @ORM\Column(name="hotsite", type="string", nullable=true, length=255)
     */
    private $hotsite;

    /**
     *
     * @var string @ORM\Column(name="corretor", type="string", nullable=true, length=255)
     */
    private $corretor;

    /**
     *
     * @var \Date @ORM\Column(name="forecast", type="date", nullable=true)
     */
    private $forecast;

    /**
     *
     * @var \Date @ORM\Column(name="done", type="date", nullable=true)
     */
    private $done;

    /**
     *
     * @var boolean 
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible = false;

    /**
     *
     * @var \Doctrine\Common\Collections\Collection 
     * @ORM\OneToMany(targetEntity="ImovelVideo", mappedBy="imovel", cascade={"all"}, orphanRemoval=true)
     */
    private $videos;

    /**
     * @ORM\ManyToMany(targetEntity="ImovelTag")
     * @ORM\JoinTable(name="imovel_tag_rel",
     * joinColumns={@ORM\JoinColumn(name="imovel_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="ImovelMakers")
     * @ORM\JoinTable(name="imovel_makers_rel",
     * joinColumns={@ORM\JoinColumn(name="imovel_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="makers_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $makers;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="imovel", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $images;
    
   /**
     * @ORM\OneToOne(targetEntity="ImovelImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToOne(targetEntity="ImovelArquivo", cascade={"all"})
     * @ORM\JoinColumn(name="arquivo_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $arquivo;
    
    /**
     * @ORM\OneToOne(targetEntity="ImovelImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="imagem_destaque_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $imagemDestaque;
    
    /**
     * @ORM\OneToOne(targetEntity="ImovelImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="bannere_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $banner;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="ENUM('Residencial', 'Comercial', 'Hotel', 'Híbrido')")
     */
    private $type;
   
    /**
     *
     * @var string @ORM\Column(name="text_location", type="string", length=255, nullable=true)
     */
    private $textLocation;
    
    /**
     *
     * @var \DateTime @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     *
     * @var \DateTime @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->address = new Address();
        $this->videos = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->makers = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * Set visible
     *
     * @param boolean $visible            
     * @return Imovel
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        
        return $this;
    }

    /**
     * Is visible
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getNameFeatured()
    {
        return $this->nameFeatured;
    }
    
    public function setNameFeatured($nameFeatured)
    {
        $this->nameFeatured = $nameFeatured;
        return $this;
    }

    public function getResume()
    {
        return $this->resume;
    }

    public function setResume($resume)
    {
        $this->resume = $resume;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getPromo()
    {
        return $this->promo;
    }

    public function setPromo($promo)
    {
        $this->promo = $promo;
        return $this;
    }

    public function getHotsite()
    {
        return $this->hotsite;
    }

    public function setHotsite($hotsite)
    {
        $this->hotsite = $hotsite;
        return $this;
    }

    public function getCorretor()
    {
        return $this->corretor;
    }

    public function setCorretor($corretor)
    {
        $this->corretor = $corretor;
        return $this;
    }

    public function getForecast()
    {
        return $this->forecast;
    }

    public function setForecast($forecast)
    {
        $this->forecast = $forecast;
        return $this;
    }

    public function getDone()
    {
        return $this->done;
    }

    public function setDone($done)
    {
        $this->done = $done;
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

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setVideos($videos)
    {
        $this->videos = $videos;
        return $this;
    }

    public function getVideos()
    {
        return $this->videos;
    }

    public function addVideo(ImovelVideo $video)
    {
        $video->setImovel($this);
        
        $this->videos->add($video);
        return $this;
    }

    public function removeVideo(ImovelVideo $video)
    {
        $this->videos->removeElement($video);
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(ImovelTag $tag)
    {
        $tag->setTag($this);
        
        $this->tags->add($tag);
        return $this;
    }

    public function removeTag(ImovelTag $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function getMakers()
    {
        return $this->makers;
    }

    public function addMakers(ImovelMakers $makers)
    {
        $makers->setMakers($this);
        
        $this->makers->add($makers);
        return $this;
    }

    public function removeMakers(ImovelMakers $makers)
    {
        $this->makers->removeElement($makers);
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

    public function getImages()
    {
        return $this->images;
    }

    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }

    public function addImage($image)
    {
        $image->setImovel($this);
        $this->images->add($image);
        return $this;
    }

    public function removeImage($image)
    {
        return $this->images->removeElement($image);
    }

    public function getLogo()
    {
        return $this->logo;
    }
    
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }
    
    public function getArquivo()
    {
        return $this->arquivo;
    }

    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function gerarUrl()
    {
        $this->url = Util::toAscii($this->getName());
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

    public function getDestaque()
    {
        return $this->destaque;
    }

    public function setDestaque($destaque)
    {
        $this->destaque = $destaque;
        return $this;
    }

    public function getAcompanhamento()
    {
        return $this->acompanhamento;
    }

    public function setAcompanhamento($acompanhamento)
    {
        $this->acompanhamento = $acompanhamento;
        return $this;
    }
    
    public function getImagesByArea($area)
	{
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("area", $area))
            ->orderBy(array("position" => Criteria::ASC));
        return $this->images->matching($criteria);
	}

    public function getVisible()
    {
        return $this->visible;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function setMakers($makers)
    {
        $this->makers = $makers;
        return $this;
    }

    public function getImagemDestaque()
    {
        return $this->imagemDestaque;
    }

    public function setImagemDestaque($imagemDestaque)
    {
        $this->imagemDestaque = $imagemDestaque;
        return $this;
    }
    
    public function getBanner()
    {
        return $this->banner;
    }
    
    public function setBanner($banner)
    {
        $this->banner = $banner;
        return $this;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getTextLocation()
    {
        return $this->textLocation;
    }

    public function setTextLocation($textLocation)
    {
        $this->textLocation = $textLocation;
        return $this;
    }
}