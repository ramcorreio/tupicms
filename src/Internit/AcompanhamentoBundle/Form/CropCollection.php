<?php
namespace Internit\AcompanhamentoBundle\Form;


class CropCollection implements \IteratorAggregate, \Countable
{

    private $crops = array();
    
    public function add($name, Crop $crop)
    {
        unset($this->crops[$name]);

        $this->crops[$name] = $crop;
    }

    public function all()
    {
        return $this->crops;
    }

    public function get($name)
    {
        return isset($this->crops[$name]) ? $this->crops[$name] : null;
    }
    
    public function getIterator()
    {
        return new \ArrayIterator($this->crops);
    }
    
    public function count()
    {
        return count($this->crops);
    }

}
