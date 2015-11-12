<?php
namespace Internit\AcompanhamentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name = "acompanhamento_acompanhamento_etapa")
 * @ORM\Entity(repositoryClass="Internit\AcompanhamentoBundle\Entity\AcompanhamentoEtapaRepository")
 */
class AcompanhamentoEtapa
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
     * @ORM\ManyToOne(targetEntity="Bloco", inversedBy="etapas")
     * @ORM\JoinColumn(name="bloco_id", referencedColumnName="id")
     **/
    private $bloco;
    
    /**
     * @ORM\ManyToOne(targetEntity="Etapa")
     * @ORM\JoinColumn(name="etapa_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $etapa;
    
    /**
     * @var valor
     *
     * @ORM\Column(name="valor", type="integer")
     */
    private $valor = 0;
    
    /**
     *
     * @var valor @ORM\Column(name="posicao", type="integer")
     */
    private $posicao = 0;
    
    /**
     *
     * @var valor @ORM\Column(name="visible", type="integer")
     */
    private $visible = 1;
    

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getEtapa()
    {
        return $this->etapa;
    }

    public function setEtapa($etapa)
    {
        $this->etapa = $etapa;
        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }
    
	public function getPosicao() 
	{
		return $this->posicao;
	}
	
	public function setPosicao($posicao)
	{
		$this->posicao = $posicao;
		return $this;
	}
	
	public function getVisible() 
	{
		return $this->visible;
	}
	
	public function setVisible($visible) 
	{
		$this->visible = $visible;
		return $this;
	}
	
	
}