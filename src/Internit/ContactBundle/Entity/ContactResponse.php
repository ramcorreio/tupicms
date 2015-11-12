<?php
namespace Internit\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ContactResponse
 *
 * @ORM\Table(name = "contact_response")
 * @ORM\Entity
 */
class ContactResponse
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
     * @ORM\ManyToOne(targetEntity="ContactRequest", inversedBy="responses")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $request;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    
    /**
     * Set id
     *
     * @return ContactResponse
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
    
    public function setRequest($request)
    {
        $this->request = $request;
         
        return $this;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * Set message
     *
     * @return ContactResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;
         
        return $this;
    }
    
    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ContactResponse
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
    
}