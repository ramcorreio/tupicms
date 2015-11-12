<?php
namespace Internit\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Banner
 *
 * @ORM\Table(name = "banner_banner")
 * @ORM\Entity(repositoryClass="Internit\BannerBundle\Entity\BannerRepository")
 */
class Banner
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
     * @var string @ORM\Column(name="url", type="string", length=1024, nullable=true)
     */
    private $url;

    /**
     *
     * @var string @ORM\Column(name="position", type="integer")
     */
    private $position = 0;

    /**
     *
     * @var integer @ORM\Column(name="target", type="string", columnDefinition="ENUM('blank', 'self')")
     */
    private $target = 'blank';

    /**
     *
     * @var boolean @ORM\Column(name="active", type="boolean")
     */
    private $active = 1;

    /**
     * @ORM\OneToOne(targetEntity="BannerImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="banner_desktop_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $imageDesktop;
    
    /**
     * @ORM\OneToOne(targetEntity="BannerImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="banner_tablet_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $imageTablet;
    
    /**
     * @ORM\OneToOne(targetEntity="BannerImageMedia", cascade={"all"})
     * @ORM\JoinColumn(name="banner_celular_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $imageCelular;
    
    /**
     * @ORM\ManyToOne(targetEntity="Internit\ImovelBundle\Entity\Imovel")
     * @ORM\JoinColumn(name="imovel_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    private $imovel;
    
    /**
     *
     * @var boolean
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible = false;
    
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

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
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

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
    
	public function getImageDesktop() 
	{
		return $this->imageDesktop;
	}
	
	public function setImageDesktop($imageDesktop) 
	{
		$this->imageDesktop = $imageDesktop;
		return $this;
	}
	
	public function getImageTablet() 
	{
		return $this->imageTablet;
	}
	
	public function setImageTablet($imageTablet) 
	{
		$this->imageTablet = $imageTablet;
		return $this;
	}
	
	public function getImageCelular()
	{
		return $this->imageCelular;
	}
	
	public function setImageCelular($imageCelular) 
	{
		$this->imageCelular = $imageCelular;
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
    
    public function getVisible()
    {
        return $this->visible;
    }
    
    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
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

	
 
}