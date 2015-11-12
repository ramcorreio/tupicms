<?php

namespace Internit\TrabalheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TrabalheGroup
 * @ORM\Table(name="trabalhe_group")
 * @ORM\Entity(repositoryClass="Internit\TrabalheBundle\Entity\TrabalheRepository")
 */
class TrabalheGroup
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\OneToMany(targetEntity="TrabalheGroupEmail", mappedBy="group", cascade={"persist","remove","merge"}, orphanRemoval=true)
     */
    private $emails;

    public function __construct()
    {
        $this->emails = new ArrayCollection();
    }
    
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return TrabalheGroup
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
     * @return TrabalheGroup
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

    public function getEmails()
    {
        return $this->emails;
    }
    
    public function addEmail(TrabalheGroupEmail $email)
    {
        $email->setGroup($this);
        
        $this->emails->add($email);
        return $this;
    }
    
    public function removeEmail(TrabalheGroupEmail $email)
    {
        $this->emails->removeElement($email);
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function __toString()
    {
    	return $this->name;
    }
	
}

