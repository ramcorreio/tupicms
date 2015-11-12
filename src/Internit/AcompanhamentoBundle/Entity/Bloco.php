<?php
namespace Internit\AcompanhamentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table(name = "acompanhamento_bloco")
 * @ORM\Entity(repositoryClass="Internit\AcompanhamentoBundle\Entity\BlocoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Bloco
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
     * @ORM\ManyToOne(targetEntity="Acompanhamento", inversedBy="blocos")
     * @ORM\JoinColumn(name="acompanhamento_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $acompanhamento;
    
    /**
     * @var string
     * @ORM\Column(name="bloco", type="string", length=255)
     */
    private $bloco;
    
   /**
     * @ORM\OneToMany(targetEntity="AcompanhamentoEtapa", mappedBy="bloco", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"posicao" = "ASC"})
     **/
    private $etapas;
    
    /**
     * @ORM\OneToMany(targetEntity="Galeria", mappedBy="bloco", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     **/
    private $galerias;
   
    /**
     * @ORM\ManyToMany(targetEntity="Relatorio", cascade={"all"})
     * @ORM\JoinTable(name="acompanhamento_bloco_relatorio",
     *      joinColumns={@ORM\JoinColumn(name="bloco_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="relatorio_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    private $relatorios;
    
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
        $this->etapas = new ArrayCollection();
        $this->galerias = new ArrayCollection();
        $this->relatorios = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getAcompanhamento()
    {
        return $this->acompanhamento;
    }
    
    public function setAcompanhamento($acompanhamento)
    {
        $this->acompanhamento = $acompanhamento;
        return $this;
    }
    
    public function getBloco()
    {
        return $this->bloco;
    }
    
    public function setBloco($bloco)
    {
        $this->bloco = $bloco;
        return $this;
    }

    public function getEtapas()
    {
        return $this->etapas;
    }

    public function setEtapas($etapas)
    {
        $this->etapas = $etapas;
        return $this;
    }
    
    public function addEtapa(AcompanhamentoEtapa $etapa) {
        $etapa->setBloco($this);
        $this->etapas->add($etapa);
        return $this;
    }
    
    public function removeEtapa(AcompanhamentoEtapa $etapa) {
        $this->etapas->removeElement($etapa);
    }
    
    public function getGalerias()
    {
        return $this->galerias;
    }
    
    public function setGalerias($galerias)
    {
        $this->galerias = $galerias;
        return $this;
    }
    
    public function addGaleria(Galeria $galeria) {
        $galeria->setBloco($this);
        $this->galerias->add($galeria);
        return $this;
    }
    
    public function removeGaleria(Galeria $galeria) {
        $this->galerias->removeElement($galeria);
    }
    
    public function getRelatorios()
    {
        return $this->relatorios;
    }
    
    public function setRelatorios($relatorios)
    {
        $this->relatorios = $relatorios;
        return $this;
    }
    
    public function addRelatorio(Relatorio $relatorio) {
        $this->relatorios->add($relatorio);
        return $this;
    }
    
    public function removeRelatorio(Relatorio $relatorio) {
        $this->relatorios->removeElement($relatorio);
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
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(){
        $this->updatedAt = new \DateTime();
    }
}