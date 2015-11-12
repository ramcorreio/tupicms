<?php
namespace Internit\RandomChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RandomChatLinks
 *
 * @ORM\Table(name = "random_chat_links")
 * @ORM\Entity(repositoryClass="Internit\RandomChatBundle\Entity\RandomChatRepository")
 */
class RandomChatLinks
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
     * @ORM\Column(name="chat", type="string")
     */
    private $chat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Set id
     *
     * @return RandomChatTurn
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set chat
     *
     * @return RandomChatLinks
     */
    public function setChat($chat)
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * Get chat
     *
     * @return string
     */
    public function getChat()
    {
        return $this->chat;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RandomChatLinks
     */
    public function setCreatedAt($createdAt)
    {
    	$this->createdAt = $createdAt;
    
    	return $this;
    }
    
    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }


    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return RandomChatLinks
     */
    public function setUpdatedAt($updatedAt)
    {
    	$this->updatedAt = $updatedAt;
    
    	return $this;
    }
    
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
    	return $this->updatedAt;
    }
}