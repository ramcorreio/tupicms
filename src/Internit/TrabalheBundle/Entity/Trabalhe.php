<?php
namespace Internit\TrabalheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trabalhe
 *
 * @ORM\Table(name = "trabalhe_trabalhe")
 * @ORM\Entity(repositoryClass="Internit\TrabalheBundle\Entity\TrabalheRepository")
 */
class Trabalhe
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
     * @ORM\ManyToOne(targetEntity="Internit\ContactBundle\Entity\ContactPerson", inversedBy="requestTrabalhe")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;
    
    /**
     * @ORM\ManyToOne(targetEntity="TrabalheSubject")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="sexo", type="text", nullable=true)
	 */
    private $sexo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="text", nullable=true)
     */
    private $facebook;
    
    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="text", nullable=true)
     */
    private $linkedin;
    
    /**
     * @var string
     *
     * @ORM\Column(name="conheceu", type="text", nullable=true)
     */
    private $conheceu;
    
    /**
    * @var string
    *
    * @ORM\Column(name="informativo", type="text", nullable=true)
    */
    private $informativo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="faixa_salarial", type="text", nullable=true)
     */
    private $faixaSalarial;
    
    /**
     * @var string
     *
     * @ORM\Column(name="escolaridade", type="text", nullable=true)
     */
    private $escolaridade;
    
    /**
     * @var string
     *
     * @ORM\Column(name="bairro", type="text", nullable=true)
     */
    private $bairro;
    
    /**
     * @ORM\ManyToOne(targetEntity="Cidade")
     * @ORM\JoinColumn(name="cidade_id", referencedColumnName="id", nullable=true)
     **/
    private $cidade;   
    
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
    
    
    
    /**
     * @ORM\OneToOne(targetEntity="Internit\ContactBundle\Entity\Arquivo", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     **/
    
    private $curriculo;
    
    
    /**
     * Set id
     *
     * @return Trabalhe
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
     * @return Trabalhe
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
     * @return Trabalhe
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
     * @return Trabalhe
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
	public function getSexo() {
		return $this->sexo;
	}
	public function setSexo( $sexo) {
		$this->sexo = $sexo;
		return $this;
	}
	public function getEstadoCivil() {
		return $this->estadoCivil;
	}
	public function setEstadoCivil($estadoCivil) {
		$this->estadoCivil = $estadoCivil;
		return $this;
	}
	public function getEndereco() {
		return $this->endereco;
	}
	public function setEndereco( $endereco) {
		$this->endereco = $endereco;
		return $this;
	}
	public function getCep() {
		return $this->cep;
	}
	public function setCep( $cep) {
		$this->cep = $cep;
		return $this;
	}
	public function getBairro() {
		return $this->bairro;
	}
	public function setBairro( $bairro) {
		$this->bairro = $bairro;
		return $this;
	}
	
	public function getCartaApresentacao() {
		return $this->cartaApresentacao;
	}
	public function setCartaApresentacao( $cartaApresentacao) {
		$this->cartaApresentacao = $cartaApresentacao;
		return $this;
	}
	public function getCurriculo() {
		return $this->curriculo;
	}
	public function setCurriculo($curriculo) {
		$this->curriculo = $curriculo;
		return $this;
	}
	public function getConheceu() {
		return $this->conheceu;
	}
	public function setConheceu( $conheceu) {
		$this->conheceu = $conheceu;
		return $this;
	}
	public function getEscolaridade() {
		return $this->escolaridade;
	}
	public function setEscolaridade( $escolaridade) {
		$this->escolaridade = $escolaridade;
		return $this;
	}
	public function getCidade() {
		return $this->cidade;
	}
	public function setCidade($cidade) {
		$this->cidade = $cidade;
		return $this;
	}
	public function getFacebook() {
		return $this->facebook;
	}
	public function setFacebook( $facebook) {
		$this->facebook = $facebook;
		return $this;
	}
	public function getLinkedin() {
		return $this->linkedin;
	}
	public function setLinkedin( $linkedin) {
		$this->linkedin = $linkedin;
		return $this;
	}
	public function getInformativo() {
		return $this->informativo;
	}
	public function setInformativo( $informativo) {
		$this->informativo = $informativo;
		return $this;
	}
	public function getFaixaSalarial() {
		return $this->faixaSalarial;
	}
	public function setFaixaSalarial( $faixaSalarial) {
		$this->faixaSalarial = $faixaSalarial;
		return $this;
	}
	
	
	
	
	
	
	
	
}