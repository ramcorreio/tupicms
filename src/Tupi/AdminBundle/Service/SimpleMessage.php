<?php
namespace Tupi\AdminBundle\Service;

use Swift_Message;

class SimpleMessage
{
    private $message;
    
    public function __construct(Swift_Message $message)
    {
        $this->message = $message; 
    }
    
    /**
     * Set the subject of this message.
     *
     * @param string $subject
     *
     * @return SimpleMessage
     */
    public function setSubject($subject)
    {
        $this->message->setSubject($subject);
    
        return $this;
    }
    
    /**
     * Get the subject of this message.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->message->getSubject();
    }
    
    /**
     * Set the date at which this message was created.
     *
     * @param int     $date
     *
     * @return SimpleMessage
     */
    public function setDate($date)
    {
        $this->message->setDate($date);
    
        return $this;
    }
    
    /**
     * Get the date at which this message was created.
     *
     * @return int
     */
    public function getDate()
    {
        return $this->message->getDate();
    }
    
    /**
     * Set the return-path (the bounce address) of this message.
     *
     * @param string $address
     *
     * @return SimpleMessage
     */
    public function setReturnPath($address)
    {
        $this->message->setReturnPath($address);
    
        return $this;
    }
    
    /**
     * Get the return-path (bounce address) of this message.
     *
     * @return string
     */
    public function getReturnPath()
    {
        
        return $this->message->getReturnPath();
    }
    
    /**
     * Set the sender of this message.
     *
     * This does not override the From field, but it has a higher significance.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return SimpleMessage
     */
    public function setSender($address, $name = null)
    {
        $this->message->setSender($address, $name);
    
        return $this;
    }
    
    /**
     * Get the sender of this message.
     *
     * @return string
     */
    public function getSender()
    {
        return $this->message->getSender();
    }
    
    /**
     * Add a From: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return SimpleMessage
     */
    public function addFrom($address, $name = null)
    {
        $this->message->addFrom($address, $name);
        
        return $this;
    }
    
    /**
     * Set the from address of this message.
     *
     * You may pass an array of addresses if this message is from multiple people.
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param string $addresses
     * @param string $name      optional
     *
     * @return SimpleMessage
     */
    public function setFrom($addresses, $name = null)
    {
        $this->message->setFrom($addresses, $name);
    
        return $this;
    }
    
    /**
     * Get the from address of this message.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->message->getFrom();
    }
    
    /**
     * Add a Reply-To: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return SimpleMessage
     */
    public function addReplyTo($address, $name = null)
    {
        $this->message->addReplyTo($address, $name);
        
        return $this;
    }
    
    /**
     * Set the reply-to address of this message.
     *
     * You may pass an array of addresses if replies will go to multiple people.
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param string $addresses
     * @param string $name      optional
     *
     * @return SimpleMessage
     */
    public function setReplyTo($addresses, $name = null)
    {
        $this->message->setReplyTo($addresses, $name);
    
        return $this;
    }
    
    /**
     * Get the reply-to address of this message.
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->message->getReplyTo();
    }
    
    /**
     * Add a To: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return SimpleMessage
     */
    public function addTo($address, $name = null)
    {
        $this->message->addTo($addresses, $name);
    
        return $this;
    }
    
    /**
     * Set the to addresses of this message.
     *
     * If multiple recipients will receive the message an array should be used.
     * Example: array('receiver@domain.org', 'other@domain.org' => 'A name')
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     *
     * @return SimpleMessage
     */
    public function setTo($addresses, $name = null)
    {
        $this->message->setTo($addresses, $name);
    
        return $this;
    }
    
    /**
     * Get the To addresses of this message.
     *
     * @return array
     */
    public function getTo()
    {
        return $this->getTo();
    }
    
    /**
     * Add a Cc: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return SimpleMessage
     */
    public function addCc($address, $name = null)
    {
        $this->message->addCc($addresses, $name);;
    
        return $this;
    }
    
    /**
     * Set the Cc addresses of this message.
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     *
     * @return SimpleMessage
     */
    public function setCc($addresses, $name = null)
    {
        $this->message->setCc($addresses, $name);
    
        return $this;
    }
    
    /**
     * Get the Cc address of this message.
     *
     * @return array
     */
    public function getCc()
    {
        return $this->message->getCc();
    }
    
    /**
     * Add a Bcc: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return SimpleMessage
     */
    public function addBcc($address, $name = null)
    {
        $this->message->addCc($addresses, $name);
    
        return $this;
    }
    
    /**
     * Set the Bcc addresses of this message.
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     *
     * @return SimpleMessage
     */
    public function setBcc($addresses, $name = null)
    {
        $this->message->setBcc($addresses, $name);
    
        return $this;
    }
    
    /**
     * Get the Bcc addresses of this message.
     *
     * @return array
     */
    public function getBcc()
    {
        return $this->message->getBcc();
    }
    
    /**
     * Set the priority of this message.
     *
     * The value is an integer where 1 is the highest priority and 5 is the lowest.
     *
     * @param int     $priority
     *
     * @return SimpleMessage
     */
    public function setPriority($priority)
    {
        $this->message->setPriority($priority);
    
        return $this;
    }
    
    /**
     * Get the priority of this message.
     *
     * The returned value is an integer where 1 is the highest priority and 5
     * is the lowest.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->message->getPriority();
    }
    
    /**
     * Ask for a delivery receipt from the recipient to be sent to $addresses
     *
     * @param array $addresses
     *
     * @return Swift_Mime_SimpleMessage
     */
    public function setReadReceiptTo($addresses)
    {
        $this->message->setReadReceiptTo($addresses);
    
        return $this;
    }
    
    /**
     * Get the addresses to which a read-receipt will be sent.
     *
     * @return string
     */
    public function getReadReceiptTo()
    {
        return $this->message->getReadReceiptTo();
    }
    
    /**
     * Set the body of this entity, either as a string
     *
     * @param mixed  $body
     * @param string $contentType optional
     * @param string $charset     optional
     *
     * @return SimpleMessage
     */
    public function setBody($body, $contentType = null, $charset = null)
    {
        $this->message->setBody($body, $contentType, $charset);
    
        return $this;
    }
    
    /**
     * Get the character set of this entity.
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->message->getCharset();
    }
    
    /**
     * Set the character set of this entity.
     *
     * @param string $charset
     *
     * @return SimpleMessage
     */
    public function setCharset($charset)
    {
        $this->message->setCharset($charset);
    
        return $this;
    }
    
    /**
     * Get the format of this entity (i.e. flowed or fixed).
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->message->getFormat();
    }
    
    /**
     * Set the format of this entity (flowed or fixed).
     *
     * @param string $format
     *
     * @return SimpleMessage
     */
    public function setFormat($format)
    {
        $this->message->setFormat($format);
        
        return $this;
    }
    
}