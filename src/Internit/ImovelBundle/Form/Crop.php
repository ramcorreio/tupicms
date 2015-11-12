<?php
namespace Internit\ImovelBundle\Form;

class Crop
{

    private $name;

    private $width;

    private $height;
    
    private $crop;
    
    public function __construct($name, $width, $height, $crop = true)
    {
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->crop = $crop;
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

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function getCrop()
    {
        return $this->crop;
    }

    public function setCrop($crop)
    {
        $this->crop = $crop;
        return $this;
    }
 
}
