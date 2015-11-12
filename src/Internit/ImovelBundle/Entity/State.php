<?php
namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="estados")
 * @ORM\Entity(repositoryClass="Internit\ImovelBundle\Entity\ImovelRepository")
 */
class State
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2)
     */
    private $uf;
    
    /**
     * @ORM\OneToMany(targetEntity="City", mappedBy="state")
     **/
    private $cities;
	
	public function __construct() {
        $this->cities = new City();
    }

	public function getId() {
		return $this->id;
	}
	
	public function setId( $id) {
		$this->id = $id;
		return $this;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	public function getCities() {
		return $this->cities;
	}
	
	public function setCities($cities) {
		$this->cities = $cities;
		return $this;
	}
	
	public function __toString()
	{
		return $this->getName();
	}
	public function getUf() {
		return $this->uf;
	}
	public function setUf($uf) {
		$this->uf = $uf;
		return $this;
	}
	

}

