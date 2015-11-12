<?php
namespace Tupi\AdminBundle\Commom;

class Util 
{
	const AUTH_ERROR = 0001;
	
	private $container;

	/**
	 * Constructor.
	 *
	 * @param ContainerInterface $container The service container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
    
    public static function toAscii($str, $replace=array(), $delimiter='-') {
    
        setlocale(LC_ALL, 'en_US.UTF8');
        if(!empty($replace)) {
        	
        	$str = str_replace((array)$replace, ' ', $str);
        }
    
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
         
        return $clean;
    }
    
    public static function removeLastComma($str) {
    
    	$str = trim($str);
	    //substr foi utilizado para remover a última vírgula
    	return substr($str, 0, strlen($str) - 1);
    }
}