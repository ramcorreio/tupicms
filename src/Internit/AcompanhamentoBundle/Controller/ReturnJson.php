<?php
namespace Internit\AcompanhamentoBundle\Controller;

class ReturnJson {
    
    private $message;
    
    private $erro;
    
    public function __construct() 
    {
        $this->erro = false;
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

    public function getErro()
    {
        return $this->erro;
    }

    public function setErro($erro)
    {
        $this->erro = $erro;
        return $this;
    }
 
    public function getJson()
    {
        return get_object_vars($this);
    }
}