<?php

namespace Internit\ImovelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImovelVideo
 *
 * @ORM\Table(name="imovel_video")
 * @ORM\Entity()
 */
class ImovelVideo
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
     * @ORM\ManytoOne(targetEntity="Imovel", inversedBy="videos")
     * @ORM\JoinColumn(name="id_imovel", referencedColumnName="id",  onDelete="CASCADE")
     */
    private $imovel;
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="title", type="string", length=255)
	 */
	private $title;
	
	/**
	 * @var string
	 * 
	 * @ORM\Column(name="video", type="string", length=255)
	 */
	private $video;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set imovel
     *
     * @param integer $imovel
     *
     * @return ImovelVideo
     */
    public function setImovel(Imovel $imovel)
    {
        $this->imovel = $imovel;

        return $this;
    }

    /**
     * Get imovel
     *
     * @return ImovelVideo
     */
    public function getImovel()
    {
        return $this->imovel;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return ImovelVideo
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }
    

	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	

 
}

