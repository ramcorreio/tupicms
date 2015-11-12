<?php
namespace Tupi\ContentBundle\Entity;

use Tupi\AdminBundle\Commom\Util;

use Tupi\ContentBundle\Entity\BaseContent;
use Tupi\ContentBundle\Types\PageStatusType;
use Tupi\ContentBundle\Twig\Renderer;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Page 
 * 
 * @ORM\Table(name = "content_page")
 * @ORM\Entity(repositoryClass="Tupi\ContentBundle\Entity\PageRepository")
 * 
 */
class Page extends BaseContent implements Renderer
{
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="body", type="text")
	 */
	private $body;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="visible", type="boolean")
	 */
	private $visible = false;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="home", type="boolean")
	 */
	private $home = false;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="header", type="string", length=1024, nullable=true)
	 */
	private $header;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="keywords", type="text", nullable=true)
	 */
	private $metaKeywords;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="description", type="text", nullable=true)
	 */
	private $description;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="Menu", mappedBy="pages")
	 */
	private $menus;
	
	/**
	 * @var status
	 *
	 * @ORM\Column(name="status", type="page_status")
	 */
	private $status = PageStatusType::DRAFT;
	
	/**
	 * @var images
	 * 
     * @ORM\ManyToMany(targetEntity="ImageMedia", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="files_pages",
     *     joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="mediafile_id", referencedColumnName="id")}
     * )
	 */
	private $images;
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="templateName", type="string", length=1024, nullable=true)
	 */
	private $templateName;
	
	
	public function __construct()
	{
		$this->images = new ArrayCollection();
		$this->menus = new ArrayCollection();
	}
	
	/**
	 * Set body
	 *
	 * @param string $body
	 * @return Page
	 */
	public function setBody($body)
	{
		$this->body = $body;
	
		return $this;
	}
	
	/**
	 * Get body
	 *
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}
	
	/**
	 * Set visible
	 *
	 * @param boolean $visible
	 * @return Page
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
	
	/**
	 * Set home
	 *
	 * @param boolean $home
	 * @return Page
	 */
	public function setHome($home)
	{
		$this->home = $home;
	
		return $this;
	}
	
	/**
	 * Is home
	 *
	 * @return boolean
	 */
	public function isHome()
	{
		return $this->home;
	}
	
	/**
	 * Set header
	 *
	 * @param string $header
	 * @return Page
	 */
	public function setHeader($header)
	{
		$this->header = $header;
	
		return $this;
	}
	
	/**
	 * Get header
	 *
	 * @return string
	 */
	public function getHeader()
	{
		return $this->header;
	}
	
	/**
	 * Set keywords
	 *
	 * @param string $keywords
	 * @return Page
	 */
	public function setMetaKeywords($metaKeywords)
	{
		$this->metaKeywords = $metaKeywords;
	
		return $this;
	}
	
	/**
	 * Get keywords
	 *
	 * @return string
	 */
	public function getMetaKeywords()
	{
		return $this->metaKeywords;
	}
	
	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Page
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	
		return $this;
	}
	
	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Set menus
	 *
	 * @param Menu $menu
	 * @return Menu
	 */
	public function setMenus($menus)
	{
		$this->menus = $menus;
	
		return $this;
	}
	
	/**
	 * Get menus
	 *
	 * @return Menu
	 */
	public function getMenus()
	{
		return $this->menus;
	}
	
	/**
	 * Set status
	 *
	 * @param status $status
	 * @return Page
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
	
	public function setImages($images)
	{
		$this->images = $images;
	
		return $this;
	}
	
	public function getImages()
	{
		return $this->images;
	}
	
	public function setTemplateName($templateName)
	{
	    $this->templateName = $templateName;
	
	    return $this;
	}
	
	public function getTemplateName()
	{
		return $this->templateName;
	}
	
	public function getShotTitle() {
		
		return substr($this->getTitle(), 0, 30) . (strlen($this->getTitle()) > 30 ? '...' : '');
		
	}
	
	public function getActiveChildren()
	{
		$criteria = Criteria::create();
		$criteria->where(Criteria::expr()->eq('visible', true));
		$criteria->andWhere(Criteria::expr()->eq('status', PageStatusType::PUBLISHED));
	
		return $this->children->matching($criteria);
	}
	
	public function getPublishedChildren()
	{
		$criteria = Criteria::create();
		$criteria->andWhere(Criteria::expr()->eq('status', PageStatusType::PUBLISHED));
	
		return $this->children->matching($criteria);
	}
}