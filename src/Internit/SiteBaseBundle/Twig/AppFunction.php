<?php
namespace Internit\SiteBaseBundle\Twig;

use Internit\SiteBaseBundle\Twig\Functions\DeviceInfor;
use Symfony\Component\HttpFoundation\RequestStack;

class AppFunction extends \Twig_Extension
{
	private $requestStack;
	
	public function __construct(RequestStack $requestStack){
		$this->requestStack = $requestStack;
	}
	
    public function getFunctions(){
    	return array(
    			new \Twig_SimpleFunction('getDevice', array($this, 'getDeviceFunction')),
    	);
    }
    
    
    public function getDeviceFunction(){
    	$device = new DeviceInfor($this->requestStack);
    	return $device->returnDevice();
    }
    
    public function getName()
    {
        return 'app_function';
    }
}