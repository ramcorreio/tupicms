<?php
namespace Internit\AtasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

use Tupi\ContentBundle\Entity\BaseContent;

/**
 * Atas
 *
 * @ORM\Table(name = "atas")
 * @ORM\Entity(repositoryClass="Internit\AtasBundle\Entity\AtasRepository")
 */
class Atas
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
	 * @ORM\Column(name="titulo", type="text")
	 */
	private $titulo;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="descricao", type="text")
	 */
	private $descricao;	
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="tags", type="text")
	 */
	private $tags;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="data_vigente", type="text")
	 */
	private $dataVigente;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active = false;
   	/**
	*
	* @var integer
	* @ORM\Column(name="posicao", type="integer")
	* @ORM\OrderBy({"posicao" = "ASC"})
	*/
	private $posicao = 0;
	
	/**
	 * @ORM\OneToOne(targetEntity="AtasArquivo", cascade={"all"})
	 * @ORM\JoinColumn(name="arquivo_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
	*/
	private $arquivo;
	
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
	
	public function __construct()
	{
		$this->atas = new ArrayCollection();
	}
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getTitulo() {
		return $this->titulo;
	}
	public function setTitulo($titulo) {
		$this->titulo = $titulo;
		return $this;
	}
	public function getDescricao() {
		return $this->descricao;
	}
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
		return $this;
	}
	public function getTags() {
		return $this->tags;
	}
	public function setTags($tags) {
		$this->tags = $tags;
		return $this;
	}
	public function getActive() {
		return $this->active;
	}
	public function setActive($active) {
		$this->active = $active;
		return $this;
	}
	public function getPosicao() {
		return $this->posicao;
	}
	public function setPosicao($posicao) {
		$this->posicao = $posicao;
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
	public function getDataVigente() {
		return $this->dataVigente;
	}
	public function setDataVigente($dataVigente) {
		$this->dataVigente = $dataVigente;
		return $this;
	}
	public function getArquivo() {
		return $this->arquivo;
	}
	public function setArquivo($arquivo) {
		$this->arquivo = $arquivo;
		return $this;
	}
	
	

	
}