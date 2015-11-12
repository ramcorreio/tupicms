<?php
namespace Internit\NewsletterBudle\Service;

use Swift_Message;
use ReflectionObject;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailService 
{
    /**
     * Stores an instance of the class
     */
    private static $instance;
    
    private $container;
    
    private function __construct()
    {   
    }
    
    /**
     * recovery a stored instance of this class
     */
    public static function getInstance(ContainerInterface $container)
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        
        self::$instance->container = $container;
        return self::$instance;
    }
    
    /**
     * Prevents that user create a copy of this instance
     */
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
    
    public function createMessage()
    {
        $internalMessage = Swift_Message::newInstance();
        return new SimpleMessage($internalMessage);
    }
    
    public function send(SimpleMessage $message) 
    {
        $refObj = new ReflectionObject($message);
        $refClass = new ReflectionClass($refObj->getName());
        
        $pMessage = $refClass->getProperty('message');
        $pMessage->setAccessible(true);
        
        $this->container->get('mailer')->send($pMessage->getValue($message));
    }
}

