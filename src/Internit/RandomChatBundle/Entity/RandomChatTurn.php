<?php
namespace Internit\RandomChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RandomChatTurn
 *
 * @ORM\Table(name = "random_chat_turn")
 * @ORM\Entity(repositoryClass="Internit\RandomChatBundle\Entity\RandomChatRepository")
 */
class RandomChatTurn
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
     * @var integer
     *
     * @ORM\Column(name="turn", type="integer", options={"default" = 0})
     */
    private $turn;

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
     * Set turn
     *
     * @return RandomChatTurn
     */
    public function setTurn($turn)
    {
        $this->turn = $turn;

        return $this;
    }

    /**
     * Get turn
     *
     * @return integer
     */
    public function getTurn()
    {
        return $this->turn;
    }
}