<?php

namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tupi\ContentBundle\Entity\Media;

/**
 * @ORM\Table(name="imovel_arquivo")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class ImovelArquivo extends Media
{
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    public function __construct()
    {
    	 parent::__construct();
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
    
    public function getType() {
    		
    	return 'imovel_arquivo';
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

