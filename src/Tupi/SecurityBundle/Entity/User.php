<?php

namespace Tupi\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Serializable;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="security_user")
 * @ORM\Entity(repositoryClass="Tupi\SecurityBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
	
	public function __construct()
	{
		$this->salt = md5(uniqid(null, true));
	}
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id
     */
    private $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="birthDate", type="datetime")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=25, unique=true)
     */
    private $login;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=40)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50)
     */
    private $role;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    /**
     * Get id
     *
     * @return integer 
     * @return User
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
    	$this->id = $id;
    
    	return $this;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
    	return $this->name;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
    	$this->name = $name;
    
    	return $this;
    }
    
    /**
     * Get birthDate
     *
     * @return datetime
     */
    public function getBirthDate()
    {
    	return $this->birthDate;
    }
    
    /**
     * Set birthDate
     *
     * @param datetime $birthDate
     * @return User
     */
    public function setBirthDate($birthDate)
    {
    	$this->birthDate = $birthDate;
    
    	return $this;
    }
    
    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;
    
        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * Set active
     *
     * @param boolean $role
     * @return User
     */
    public function setRole($role)
    {
    	$this->role = $role;
    
    	return $this;
    }
    
    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
    	return $this->role;
    }
    
    /**
     * @see UserInterface::getUsername()
     */
    public function getUsername() 
    {
    	return $this->login;
    }
    
    /**
     * @see UserInterface::getRoles()
     */
    public function getRoles()
    {
    	return array($this->role);
    }
    
    /**
     *
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
    	return false;
    }
    
    /**
     * @see Serializable::serialize()
     */
    public function serialize()
    {
    	 return serialize(array($this->id));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
    	list ($this->id) = unserialize($serialized);
    }
    
    /**
     * @return boolean
     */
    public function getStatus()
    {
    	return ($this->active === true ? 'Ativo' : 'Inativo');
    }
    
    /**
     * @see AdvancedUserInterface::isAccountNonExpired()
     */
    public function isAccountNonExpired()
    {
    	return true;
    }
    
    /**
     * @see AdvancedUserInterface::isAccountNonLocked()
     */
    public function isAccountNonLocked()
    {
    	return true;
    }
    
    /**
     * @see AdvancedUserInterface::isCredentialsNonExpired()
     */
    public function isCredentialsNonExpired()
    {
    	return true;
    }
    
    /**
     * @see AdvancedUserInterface::isEnabled()
     */
    public function isEnabled()
    {
    	return $this->active;
    }
}
