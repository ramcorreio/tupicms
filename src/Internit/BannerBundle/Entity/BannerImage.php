<?php

namespace Internit\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="banner_image")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class BannerImage extends BannerImageMedia
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
     * @ORM\ManyToOne(targetEntity="Internit\ImovelBundle\Entity\Imovel", inversedBy="images")
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
    
    public function getBanner()
    {
        return $this->banner;
    }
    
    public function setBanner($banner)
    {
        $this->banner = $banner;
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
        return 'banner_image';
    }




 

}

