<?php
namespace Internit\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ContactConfig
 *
 * @ORM\Table(name = "contact_config")
 * @ORM\Entity
 */
class ContactConfig
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
     * @ORM\Column(name="mensagem_resposta", type="text")
     */
    private $mensagemResposta;
    
    /**
     * Set id
     *
     * @return ContactResponse
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
    
	public function getMensagemResposta() {
		return $this->mensagemResposta;
	}
	public function setMensagemResposta($mensagemResposta) {
		$this->mensagemResposta = $mensagemResposta;
		return $this;
	}
	
    
}