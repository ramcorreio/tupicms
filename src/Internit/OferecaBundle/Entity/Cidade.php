<?php

namespace Internit\OferecaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="ofereca_cidade")
 * @ORM\Entity(repositoryClass="Internit\OferecaBundle\Entity\CidadeRepository")
 */
class Cidade
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
     * @ORM\ManyToOne(targetEntity="Internit\OferecaBundle\Entity\Estado", inversedBy="cidades")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     **/
    private $estado;

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
	
	public function getEstado() {
		return $this->estado;
	}
	
	public function setEstado($estado) {
		$this->estado = $estado;
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

