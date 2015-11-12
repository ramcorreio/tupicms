<?php

namespace Tupi\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseContent
 *
 * @ORM\Table(name = "content_base")
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class BaseContent extends LinkContent
{
    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=1024, nullable=true)
     */
    private $summary;
    
    /**
     * @var string $source
     *
     * @ORM\Column(name="source", type="string", length=100, nullable=true)
     */
    private $source;

    /**
     * Set summary
     *
     * @param string $summary
     * @return BaseContent
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    
        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }
    
    
    /**
     * Set source
     *
     * @param string $source
     * @return BaseContent
     */
    public function setSource($source)
    {
    	$this->source = $source;
    
    	return $this;
    }
    
    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
    	return $this->source;
    }

}
