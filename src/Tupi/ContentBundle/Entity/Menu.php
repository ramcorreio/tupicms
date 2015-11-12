<?php

namespace Tupi\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

use Tupi\ContentBundle\Entity\LinkContent;
use Tupi\ContentBundle\Twig\Renderer;
use Tupi\ContentBundle\Types\PageStatusType;

/**
 * @ORM\Table(name="content_menu")
 * @ORM\Entity(repositoryClass="Tupi\ContentBundle\Entity\MenuRepository")
 * 
 * @ORM\HasLifecycleCallbacks
 */
class Menu extends LinkContent implements Renderer {
    
	/**
	 * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="set null")
	 */
	private $parent;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active = false;
	
	
	/**
	 * @var children
	 * 
	 * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent", cascade={"remove"}, orphanRemoval=true)
	 * @ORM\OrderBy({"position" = "ASC"})
	 */
	private $children;
	
	/**
	 * @var pages
	 *
     * @ORM\ManyToMany(targetEntity="Page", inversedBy="menus")
     * @ORM\JoinTable(name="content_menu_page")
     *
	 */
	private $pages;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="templateName", type="string", length=1024, nullable=true)
	 */
	private $templateName;
	
		
	/**
	 * @var idLink
	 *
	 * @ORM\OneToMany(targetEntity="Menu", mappedBy="id", orphanRemoval=true)
	 * @ORM\OrderBy({"position" = "ASC"})
	 */
	private $idLink;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="Menu", inversedBy="idLink")
	 * @ORM\JoinColumn(name="menuRedirect", referencedColumnName="id", nullable=true, onDelete="restrict")
	 */
	private $menuRedirect;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="redirect", type="boolean")
	 */
	private $redirect = false;
	
	
	
   /**
	*
	* @var integer
	* @ORM\Column(name="position", type="integer")
	*/
	private $position = 0;
	
	
	public function __construct()
	{
		$this->children = new ArrayCollection();
		$this->pages = new ArrayCollection();
	}
	
	public function getParent() {
		return $this->parent;
	}
	
	public function setParent($parent) {
		
		$this->parent = $parent;
		return $this;
	}
	
	/**
	 * Set active
	 *
	 * @param boolean $active
	 * @return Menu
	 */
	public function setActive($active)
	{
		$this->active = $active;
	
		return $this;
	}
	
	/**
	 * Is active
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active;
	}
	
	public function getChildren() {
		
		return $this->children;
	}
	
	public function setChildren($children) {
		
		$this->children = $children;
		return $this;
	}
	
	public function addChildren(Menu $children) {
		
	    $this->children->add($children);
	    return $this;
	}
	
	public function getPages()
	{
	    return $this->pages;
	}
	
	public function setPages($pages)
	{
	    $this->pages = $pages;
	    return $this;
	}
	
	public function addPage(Page $page) {
	    $this->pages->add($page);
	    return $this;
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
	
	public function getPosition()
	{
	    return $this->position;
	}
	
	public function setPosition($position)
	{
	    $this->position = $position;
	    return $this;
	}
	
	public function getActiveChildren()
	{
		$criteria = Criteria::create();
		$criteria->where(Criteria::expr()->eq('active', true));
		$criteria->andWhere(Criteria::expr()->eq('redirect', false));
		$criteria->orderBy(array("position" => Criteria::ASC));
	
		return $this->children->matching($criteria);
	}
	
	public function getPublishedPages()
	{	
		return $this->pages->filter(function($entry) {
			return $entry->getStatus() == PageStatusType::PUBLISHED;
		});
	}
	
	public function isRedirect() 
	{
		return $this->redirect;
	}
	
	public function setRedirect($redirect) 
	{
		$this->redirect = $redirect;
		
		return $this;
	}
	
	public function getMenuRedirect() 
	{
		
		return $this->menuRedirect;
	}
	
	public function setMenuRedirect($menuRedirect) 
	{
		$this->menuRedirect = $menuRedirect;
		
		return $this;
	}
	
	public function __toString()
	{
		return '[' . 
			'id: ' . parent::getId() . ', ' . 
			'title: ' . parent::getTitle() . ', ' . 
			'url: ' . parent::getUrl() . ', ' . 
			'parent: ' . (is_null($this->parent) ? null : $this->parent->getId() ) . ', ' .
			'active: ' . $this->active .
			'position: ' . $this->position .
		']';
	}
}