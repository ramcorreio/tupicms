<?php
namespace Internit\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ContactPerson
 *
 * @ORM\Table(name = "contact_person")
 * @ORM\Entity(repositoryClass="Internit\ContactBundle\Entity\ContactPersonRepository")
 */
class ContactPerson
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
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", unique=true, length=50)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=255, nullable=true)
     */
    
    private $telefone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=255, nullable=true)
     */
    private $celular;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nascimento", type="datetime", nullable=true)
     */
    private $nascimento;
    
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
     * @ORM\OneToMany(targetEntity="ContactRequest", mappedBy="person")
     */
    private $requests;
    
    /**
     * @ORM\OneToMany(targetEntity="Internit\TrabalheBundle\Entity\Trabalhe", mappedBy="person")
     */
    private $requestTrabalhe;
    
    /**
     * @ORM\OneToMany(targetEntity="Internit\OferecaBundle\Entity\Ofereca", mappedBy="person")
     */
    private $requestOfereca;
    
    

    public function __construct()
    {
        $this->requests = new ArrayCollection();
        $this->requestsTrabalhe = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @return ContactPerson
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


    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }
    
    public function setCelular($celular)
    {
        $this->celular = $celular;
    
        return $this;
    }
    
    public function getCelular()
    {
        return $this->celular;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ContactPerson
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
     * @return ContactPerson
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

    public function setRequests($requests)
    {
        $this->requests = $requests;

        return $this;
    }

    public function getRequests()
    {
        return $this->requests;
    }

	public function getNascimento() {
		return $this->nascimento;
	}
	public function setNascimento(\DateTime $nascimento) {
		$this->nascimento = $nascimento;
		return $this;
	}
	public function getRequestTrabalhe() {
		return $this->requestTrabalhe;
	}
	public function setRequestTrabalhe($requestTrabalhe) {
		$this->requestTrabalhe = $requestTrabalhe;
		return $this;
	}
	
	
	
	
	
}