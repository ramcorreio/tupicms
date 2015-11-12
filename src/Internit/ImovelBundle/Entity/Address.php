<?php
namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 * @ORM\Table(name = "endereco")
 * @ORM\Entity()
 */
class Address
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
	 * @ORM\Column(name="street", type="string", length=255)
	 */
	private $street;
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="zipcode", type="string", length=30)
	 */
	private $zipcode;
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="number", type="string", length=11)
	 */
	private $number;
    
    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     **/
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=255)
     */
    private $district;
    
    /**
     * @var string
     *
     * @ORM\Column(name="refer", type="text", nullable=true)
     */
    private $refer;

    public function __construct()
    {
        $this->city = new City();
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

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getRefer()
    {
        return $this->refer;
    }

    public function setRefer($refer)
    {
        $this->refer = $refer;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getDistrict()
    {
        return $this->district;
    }

    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }
}