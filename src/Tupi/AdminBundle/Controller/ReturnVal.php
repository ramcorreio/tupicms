<?php
namespace Tupi\AdminBundle\Controller;

class ReturnVal {
    
    private $message;
    
    private $route;
    
    public function __construct() 
    {
        $this->route = 'default';
        $this->message = 'Dados salvo com sucesso!';
    }
    
    public function getMessage() 
    {   
        return $this->message;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
        
        return $this;
    }
    
    public function getRoute()
    {
        return $this->route;
    }
    
    public function setRoute($route)
    {
        $this->route = $route;
    
        return $this;
    }
}