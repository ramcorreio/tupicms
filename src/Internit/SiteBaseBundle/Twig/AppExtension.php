<?php
namespace Internit\SiteBaseBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dataExtenso', array($this, 'dataExtensoFilter')),
        	new \Twig_SimpleFilter('dataGetYear', array($this, 'dataGetYearFilter')),
        		new \Twig_SimpleFilter('dataEntrega', array($this, 'dataEntregaFilter')),
        );
    }

    public function dataExtensoFilter($date)
    {
    	if(empty($date)){
    		return "";
    	}
        $months = array(1 => 'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
        list($day, $month, $year) = explode("/", $date->format("d/n/Y"));
        $month = $months[$month];
        
        return "{$day} de {$month} de {$year}";
    }
	
    public function dataGetYearFilter($date)
    {
    	if(empty($date)){
    		return "";
    	}
    	return $date->format("Y");
    }
    
    public function dataEntregaFilter($date)
    {
    	if(empty($date)){
    		return "";
    	}
        $months = array(1 => 'JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ');
        list($month, $year) = explode("/", $date->format("n/Y"));
        $month = $months[$month];
        
        return "{$month}/{$year}";
    }
    
    public function getName()
    {
        return 'app_extension';
    }
}