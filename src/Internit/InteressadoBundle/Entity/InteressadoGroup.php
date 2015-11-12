<?php

namespace Internit\InteressadoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * InteressadoGroup
 * @ORM\Table(name="interessado_group")
 * @ORM\Entity(repositoryClass="Internit\InteressadoBundle\Entity\InteressadoRepository")
 */
class InteressadoGroup
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
     * @ORM\OneToMany(targetEntity="InteressadoGroupEmail", mappedBy="group", cascade={"persist","remove","merge"}, orphanRemoval=true)
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
     * @return InteressadoGroup
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
     * @return InteressadoGroup
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
    
    public function addEmail(InteressadoGroupEmail $email)
    {
        $email->setGroup($this);
        
        $this->emails->add($email);
        return $this;
    }
    
    public function removeEmail(InteressadoGroupEmail $email)
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

