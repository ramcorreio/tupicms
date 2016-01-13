<?php

namespace Internit\AcompanhamentoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tupi\ContentBundle\Entity\Media;

/**
 * @ORM\Table(name="acompanhamento_relatorio")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Relatorio extends Media
{
    public function __construct()
    {
        parent::__construct();
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
    
    public function getType()
    {
        return "relatorio";    
    }
}