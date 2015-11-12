<?php
namespace Internit\OferecaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ofereca_arquivo")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Arquivo
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Tupi\ContentBundle\Entity\ImageMedia" , cascade={"all"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity="Ofereca", inversedBy="arquivos")
     * @ORM\JoinColumn(name="ofereca_id", referencedColumnName="id")
     */
    private $ofereca;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getMedia()
    {
        return $this->media;
    }

    public function setMedia($media)
    {
        $this->media = $media;
        return $this;
    }

    public function getEstabelecimento()
    {
        return $this->estabelecimento;
    }

    public function setEstabelecimento($estabelecimento)
    {
        $this->estabelecimento = $estabelecimento;
        return $this;
    }

    public function getType()
    {
        return 'estabelecimento_arquivo';
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
	public function getOfereca() {
		return $this->ofereca;
	}
	public function setOfereca($ofereca) {
		$this->ofereca = $ofereca;
		return $this;
	}
	
}

