<?php

namespace Tupi\SecurityBundle\Manager;

class LinksComuns
{
    private
        $params
        ;

    public function __construct(array $params)
    {
        $this->params = $params;
    }
    
    public function getParameters()
    {
        return $this->params;
    }

}
