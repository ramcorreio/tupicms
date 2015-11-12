<?php
namespace Internit\OferecaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ofereca
 *
 * @ORM\Table(name = "ofereca_ofereca")
 * @ORM\Entity(repositoryClass="Internit\OferecaBundle\Entity\OferecaRepository")
 */
class Ofereca
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
     * @ORM\ManyToOne(targetEntity="Internit\ContactBundle\Entity\ContactPerson", inversedBy="requestOfereca")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;
    
    /**
     * @ORM\ManyToOne(targetEntity="OferecaSubject")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     */
    private $subject;
    
    /**
     * @var string
     *
     * @ORM\Column(name="statusUsuario", type="text", nullable=true)
     */
    private $statusUsuario;
    
    /**
	 * @var string
	 * 
	 * @ORM\Column(name="endereco", type="text", nullable=true)
	 */
    private $endereco;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cep", type="text", nullable=true)
     */
    private $cep;
    
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
     * @ORM\Column(name="referencia", type="text", nullable=true)
     */
    private $referencia;
    
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
     * @ORM\OneToMany(targetEntity="Arquivo", mappedBy="ofereca", cascade={"all"}, orphanRemoval=true)
     **/
    private $arquivos;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="localizacao", type="text", nullable=true)
     */
    private $localizacao;
    
    /**
     * @var string
     *
     * @ORM\Column(name="area", type="text", nullable=true)
     */
    private $area;
    
    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="text", nullable=true)
     */
    private $valor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="forma_pagamento", type="text", nullable=true)
     */
    private $formaPagamento;
    
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
     * @var text
     *
     * @ORM\Column(name="observacao", type="text", nullable=true)
     */
    private $observacao;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getSubject() {
		return $this->subject;
	}
	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}
	public function getEndereco() {
		return $this->endereco;
	}
	public function setEndereco($endereco) {
		$this->endereco = $endereco;
		return $this;
	}
	public function getCep() {
		return $this->cep;
	}
	public function setCep($cep) {
		$this->cep = $cep;
		return $this;
	}
	public function getBairro() {
		return $this->bairro;
	}
	public function setBairro($bairro) {
		$this->bairro = $bairro;
		return $this;
	}
	public function getCidade() {
		return $this->cidade;
	}
	public function setCidade($cidade) {
		$this->cidade = $cidade;
		return $this;
	}
	public function getSentToGroup() {
		return $this->sentToGroup;
	}
	public function setSentToGroup($sentToGroup) {
		$this->sentToGroup = $sentToGroup;
		return $this;
	}
	public function getSentToEmails() {
		return $this->sentToEmails;
	}
	public function setSentToEmails($sentToEmails) {
		$this->sentToEmails = $sentToEmails;
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
	public function getLocalizacao() {
		return $this->localizacao;
	}
	public function setLocalizacao($localizacao) {
		$this->localizacao = $localizacao;
		return $this;
	}
	public function getArea() {
		return $this->area;
	}
	public function setArea($area) {
		$this->area = $area;
		return $this;
	}
	public function getValor() {
		return $this->valor;
	}
	public function setValor($valor) {
		$this->valor = $valor;
		return $this;
	}
	public function getFormaPagamento() {
		return $this->formaPagamento;
	}
	public function setFormaPagamento($formaPagamento) {
		$this->formaPagamento = $formaPagamento;
		return $this;
	}
	public function getConheceu() {
		return $this->conheceu;
	}
	public function setConheceu($conheceu) {
		$this->conheceu = $conheceu;
		return $this;
	}
	public function getInformativo() {
		return $this->informativo;
	}
	public function setInformativo($informativo) {
		$this->informativo = $informativo;
		return $this;
	}
	public function getObservacao() {
		return $this->observacao;
	}
	public function setObservacao($observacao) {
		$this->observacao = $observacao;
		return $this;
	}
	public function getReferencia() {
		return $this->referencia;
	}
	public function setReferencia($referencia) {
		$this->referencia = $referencia;
		return $this;
	}
	public function getPerson() {
		return $this->person;
	}
	public function setPerson($person) {
		$this->person = $person;
		return $this;
	}
	public function getArquivos() {
		return $this->arquivos;
	}
	public function setArquivos($arquivos) {
		$this->arquivos = $arquivos;
		return $this;
	}
	public function getStatusUsuario() {
		return $this->statusUsuario;
	}
	public function setStatusUsuario($statusUsuario) {
		$this->statusUsuario = $statusUsuario;
		return $this;
	}
	
	
	
	
	
	
	
	
}