<?php

namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="imovel_image")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Image extends ImovelImageMedia
{    
    
    /**
     *
     * @var string @ORM\Column(name="position", type="integer")
     */
    private $position = 0;
    
    /**
     *
     * @var string @ORM\Column(name="area", type="string")
     */
    private $area;
    
    /**
     * @ORM\ManyToOne(targetEntity="Imovel", inversedBy="images")
     * @ORM\JoinColumn(name="imovel_id", referencedColumnName="id")
     **/
    private $imovel;
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }
    
    public function getArea()
    {
        return $this->area;
    }
    
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }
    
    public function getImovel()
    {
        return $this->imovel;
    }
    
    public function setImovel($imovel)
    {
        $this->imovel = $imovel;
        return $this;
    }
    
    public function getExtension()
    {
        $extension = array (
            'image/jpg' => 'jpg',
            'image/jpeg' => 'jpeg',
            'image/gif' => 'gif',
            'image/png' => 'png',
            'image/bmp' => 'bmp'
        );
         
        return $extension[$this->getMimeType()];
    }
    
    public function getType()
    {
        return 'imovel_image';
    }

}

