<?php
namespace Internit\InteressadoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interessado
 *
 * @ORM\Table(name = "interessado_interessado")
 * @ORM\Entity(repositoryClass="Internit\InteressadoBundle\Entity\InteressadoRepository")
 */
class Interessado
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
     * @ORM\ManyToOne(targetEntity="Internit\ContactBundle\Entity\ContactPerson", inversedBy="requestInteressado")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;
    
    /**
     * @ORM\ManyToOne(targetEntity="InteressadoSubject")
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
     * @ORM\ManyToOne(targetEntity="Internit\ImovelBundle\Entity\Imovel")
     * @ORM\JoinColumn(name="imovel_id", referencedColumnName="id")
     */
    private $imovel;    
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="sent_group", type="text", nullable=true)
	 */
    private $sentToGroup;
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="sent_emails", type="text", nullable=true)
	 */
    private $sentToEmails;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
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

    public function getPerson()
    {
        return $this->person;
    }

    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
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

    public function getSentToGroup()
    {
        return $this->sentToGroup;
    }

    public function setSentToGroup($sentToGroup)
    {
        $this->sentToGroup = $sentToGroup;
        return $this;
    }

    public function getSentToEmails()
    {
        return $this->sentToEmails;
    }

    public function setSentToEmails($sentToEmails)
    {
        $this->sentToEmails = $sentToEmails;
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