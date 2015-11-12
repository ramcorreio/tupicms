<?php

namespace Internit\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="banner_image_media_thumb")
 * @ORM\Entity()
 */
class Thumb
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
     * @ORM\Column(name="name", type="string")
     */
    private $name;
      
    /**
     * @var text
     * 
     * @ORM\Column(name="value", type="text")
     **/
    private $value;
    
    /**
     * @ORM\ManyToOne(targetEntity="BannerImageMedia", inversedBy="thumbs")
     * @ORM\JoinColumn(name="image_media_id", referencedColumnName="id")
     **/
    private $imageMedia;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getImageMedia()
    {
        return $this->imageMedia;
    }

    public function setImageMedia($imageMedia)
    {
        $this->imageMedia = $imageMedia;
        return $this;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

}

