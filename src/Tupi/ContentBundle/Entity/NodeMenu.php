<?php

namespace Tupi\ContentBundle\Entity;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class NodeMenu implements \Serializable
{
    private $id;
    
    private $parent;
    
    private $text;
    
    private $children = array();
    
    private $serializer;
    
    private $position;
    
    public function __construct() 
    {
    	$encoders = array(new JsonEncoder());
    	$normalizers = array(new GetSetMethodNormalizer());
    	$this->serializer = new Serializer($normalizers, $encoders);
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

    public function getParent()
    {
    	return $this->parent;
    }
    
    public function setParent($parent)
    {
    	$this->parent = $parent;
    	return $this;
    }
    
    public function getText()
    {
    	return $this->text;
    }
    
    public function setText($text)
    {
    	$this->text = $text;
    	return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function addChild($children)
    {
        $this->children[] = $children;
        return $this;
    }
    
    public function getPosition()
    {
    	return $this->position;
    }
    
    public function setPosition($position)
    {
    	$this->position = $position;
    	return $this;
    }
    
    public function serialize() {
    	
        return $this->serializer->serialize($this, 'json');
    }
    
    public function unserialize($serialized) {
        return $this->serializer->deserialize(json_encode($serialized), __CLASS__, 'json');
    }
    
    public function __toString() 
    {
    	return $this->serialize();	
    }
  
}