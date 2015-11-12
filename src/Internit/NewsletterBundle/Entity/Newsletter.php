<?php
namespace Internit\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name = "newsletter")
 * @ORM\Entity(repositoryClass="Internit\NewsletterBundle\Entity\NewsletterRepository")
 * 
 */
class Newsletter
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, unique = true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * Set id
     *
     * @return Newsletter
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

    public function getTelefone() {
    	return $this->telefone;
    }
    public function setTelefone($telefone) {
    	$this->telefone = $telefone;
    	return $this;
    }
    public function getCelular() {
    	return $this->celular;
    }
    public function setCelular($celular) {
    	$this->celular = $celular;
    	return $this;
    }
    
    
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }
    public function getPerson() {
    	return $this->person;
    }
    public function setPerson($person) {
    	$this->person = $person;
    	return $this;
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
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Newsletter
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
 
}