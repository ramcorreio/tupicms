<?php
namespace Internit\SiteBaseBundle\Twig\Functions;

use Symfony\Component\HttpFoundation\RequestStack;

class DeviceInfor
{
	private $tablet_browser = 0;
	private $mobile_browser = 0;
	private $server;
	private $httpUserAgent;
	private $httpAccept;
	private $httpXWapProfile;
	private $httpProfile;
	private $httpXOperaminiPhoneUa;
	private $httpDeviceStockUa;
	
	public function __construct(RequestStack $requestStack){
		$this->server = $requestStack->getCurrentRequest()->server->all();
		$this->httpUserAgent = $this->server['HTTP_USER_AGENT'];
		$this->httpAccept = $this->server['HTTP_ACCEPT'];
		$this->httpXWapProfile = isset($this->server['HTTP_X_WAP_PROFILE'])? true : false;
		$this->httpProfile = isset($this->server['HTTP_PROFILE'])? true : false;
		$this->httpDeviceStockUa = isset($this->server['HTTP_DEVICE_STOCK_UA']) ? $this->server['HTTP_DEVICE_STOCK_UA']:'';
		$this->httpXOperaminiPhoneUa = isset($this->server['HTTP_X_OPERAMINI_PHONE_UA']) ? $this->server['HTTP_X_OPERAMINI_PHONE_UA'] : $this->httpDeviceStockUa;
	}
	
	public function returnDevice(){
		
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($this->httpUserAgent))) {
			$this->tablet_browser++;
		}
		
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($this->httpUserAgent))) {
			$this->mobile_browser++;
		}
		
		if ((strpos(strtolower($this->httpAccept),'application/vnd.wap.xhtml+xml') > 0) or (($this->httpXWapProfile or $this->httpProfile))) {
			$this->mobile_browser++;
		}
		
		$mobile_ua = strtolower(substr($this->httpUserAgent, 0, 4));
		$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda ','xda-');
		
		if (in_array($mobile_ua,$mobile_agents)) {
			$this->mobile_browser++;
		}
		
		if (strpos(strtolower($this->httpUserAgent),'opera mini') > 0) {
			$this->mobile_browser++;
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower($this->httpXOperaminiPhoneUa);
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				$this->tablet_browser++;
			}
		}
		
		if ($this->tablet_browser > 0) {
			// do something for tablet devices
			return 'tablet';
		}else if ($this->mobile_browser > 0) {
			// do something for mobile devices
			return 'mobile';
		}else {
			// do something for everything else
			return 'desktop';
		}		
		
	}
	
}