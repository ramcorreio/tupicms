<?php

namespace Internit\TrabalheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="trabalhe_estado")
 * @ORM\Entity(repositoryClass="Internit\TrabalheBundle\Entity\EstadoRepository")
 */
class Estado
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
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;
    
    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2)
     */
    private $uf;
    
    /**
     * @ORM\OneToMany(targetEntity="Cidade", mappedBy="estado")
     **/
    private $cidades;
    
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
	
	public function __construct() {
        $this->cidades = new Cidade();
    }

	public function getId() {
		return $this->id;
	}
	
	public function setId( $id) {
		$this->id = $id;
		return $this;
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
		return $this;
	}
	
	public function getUf()
	{
	    return $this->uf;
	}
	
	public function setUf($uf)
	{
	    $this->uf = $uf;
	    return $this;
	}
	
	public function getCidades() {
		return $this->cidades;
	}
	
	public function setCidades($cidades) {
		$this->cidades = $cidades;
		return $this;
	}
	
	public function getCreatedAt() {
		return $this->createdAt;
	}
	
	public function setCreatedAt(\DateTime $createdAt) {
		$this->createdAt = $createdAt;
		return $this;
	}
	
	public function getUpdatedAt() {
		return $this->updatedAt;
	}
	
	public function setUpdatedAt(\DateTime $updatedAt) {
		$this->updatedAt = $updatedAt;
		return $this;
	}
	
	public function __toString()
	{
		return $this->getNome();
	}
}

