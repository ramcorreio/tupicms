<?php

namespace Internit\InteressadoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InteressadoGroupEmail
 *
 * @ORM\Table(name="interessado_group_email")
 * @ORM\Entity(repositoryClass="Internit\InteressadoBundle\Entity\InteressadoRepository")
 */
class InteressadoGroupEmail
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
     * @ORM\ManyToOne(targetEntity="InteressadoGroup", inversedBy="emails")
     * @ORM\JoinColumn(name="id_group", referencedColumnName="id",  onDelete="CASCADE")
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;


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
     * Set group
     *
     * @param integer $group
     *
     * @return InteressadoGroupEmail
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return InteressadoGroupEmail
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return InteressadoGroupEmail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    public function addEmail(InteressadoGroupEmail $email)
    {
        if (!$this->emails->contains($email)) {
            $this->emails->add($email);
        }
    
        return $this;
    }

 
}

