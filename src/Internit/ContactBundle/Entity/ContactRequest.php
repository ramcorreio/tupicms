<?php
namespace Internit\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ContactRequest
 *
 * @ORM\Table(name = "contact_request")
 * @ORM\Entity(repositoryClass="Internit\ContactBundle\Entity\ContactRepository")
 */
class ContactRequest
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
     * @ORM\ManyToOne(targetEntity="ContactPerson", inversedBy="requests")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;
    
    /**
     * @ORM\ManyToOne(targetEntity="ContactSubject")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="message", type="text")
	 */
    private $message;
    
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="informativo", type="string", length=50)
     */
    private $informativo;
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="sent_group", type="text")
	 */
    private $sentToGroup;
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="sent_emails", type="text")
	 */
    private $sentToEmails;
    
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
     * @ORM\OneToMany(targetEntity="ContactResponse", mappedBy="request")
     */
    private $responses;
    
    /**
     * @var string
     *
     * @ORM\Column(name="conheceu", type="text", nullable=true)
     */
    private $conheceu;
    
    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @return ContactRequest
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
    
    public function setPerson($person)
    {
        $this->person = $person;
         
        return $this;
    }
    
    public function getPerson()
    {
        return $this->person;
    }
    
    public function setSubject($subject)
    {
        $this->subject = $subject;
         
        return $this;
    }
    
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * Set message
     *
     * @return ContactRequest
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
    
    public function setResponses($responses)
    {
        $this->responses = $responses;
    
        return $this;
    }
    
    public function getResponses()
    {
        return $this->responses;
    }
    
    /**
     * Set sentToEmails
     *
     * @return sentToEmails
     */
    public function setSentToEmails($sentToEmails)
    {
        $this->sentToEmails = $sentToEmails;
         
        return $this;
    }
    
    /**
     * Get sentToEmails
     *
     * @return string
     */
    public function getSentToEmails()
    {
        return $this->sentToEmails;
    }
    
    /**
     * Set sentToGroup
     *
     * @return sentToGroup
     */
    public function setSentToGroup($sentToGroup)
    {
        $this->sentToGroup = $sentToGroup;
         
        return $this;
    }
    
    /**
     * Get sentToGroup
     *
     * @return string
     */
    public function getSentToGroup()
    {
        return $this->sentToGroup;
    }
	public function getInformativo() {
		return $this->informativo;
	}
	public function setInformativo($informativo) {
		$this->informativo = $informativo;
		return $this;
	}
	public function getConheceu() {
		return $this->conheceu;
	}
	public function setConheceu($conheceu) {
		$this->conheceu = $conheceu;
		return $this;
	}
	
	
	
}