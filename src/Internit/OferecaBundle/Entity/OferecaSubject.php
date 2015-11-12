<?php
namespace Internit\OferecaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tupi\AdminBundle\Types\StatusType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * OferecaSubject
 *
 * @ORM\Table(name = "ofereca_subject")
 * @ORM\Entity(repositoryClass="Internit\OferecaBundle\Entity\OferecaRepository")
 */
class OferecaSubject
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
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150)
     */
    private $description;
    
    /**
     * @var status
     *
     * @ORM\Column(name="status", type="status")
     */
    private $status = StatusType::ACTIVE;
    
    /**
     * @var randomStatus
     *
     * @ORM\Column(name="randomStatus", type="status")
     */
    private $randomStatus = StatusType::INACTIVE;
 
    /**
     * @ORM\ManyToMany(targetEntity="OferecaGroup") 
     * @ORM\JoinTable(name="ofereca_subject_group",
     *      joinColumns={@ORM\JoinColumn(name="subject_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     **/
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @return OferecaSubject
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
     * @return OferecaSubject
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
     * Set description
     *
     * @return OferecaSubject
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
     * Set status
     *
     * @param status $status
     * @return Media
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
    
    /**
     * Set randomStatus
     *
     * @param randomStatus $randomStatus
     */
    public function setRandomStatus($randomStatus)
    {
        $this->randomStatus = $randomStatus;
    
        return $this;
    }
    
    /**
     * Get randomStatus
     *
     * @return randomStatus
     */
    public function getRandomStatus()
    {
        return $this->randomStatus;
    }

    public function setGroups($groups) {
    	$this->groups = $groups;
    	return $this;
    }
    
    public function getGroups()
    {
    	return $this->groups;
    }

    public function addGroup(OferecaGroup $group)
    {
    	$group->setGroup($this);
    
    	$this->groups->add($group);
    	return $this;
    }
    
    public function removeGroup(OferecaGroup $group)
    {
    	$this->groups->removeElement($group);
    }
	
}