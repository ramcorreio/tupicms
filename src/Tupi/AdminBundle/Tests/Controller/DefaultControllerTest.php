<?php

namespace Tupi\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$browser = static::createClient();
		$crawler = $browser->request('GET', '/admin/login');
		
		$this->assertEquals(200, $browser->getResponse()->getStatusCode());		
		$this->assertTrue($crawler->filter('html:contains("Entrar")')->count() > 0);
		
		return array($browser, $crawler);
	}
	
	/**
     * @depends testIndex
     */
	public function testLogin(array $values)
	{
		list($browser, $crawler) = $values;
		
		// set some values
		$form = $crawler->selectButton('entrar')->form(array(
			'_username'  => 'admin',
			'_password'  => 'teste'
		));

		// submit the form
		$browser->submit($form);
		$crawler = $browser->followRedirect();
        
        $this->assertEquals(200, $browser->getResponse()->getStatusCode());
        $crawler = $browser->request('GET', '/admin');
        
        $this->assertEquals(200, $browser->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Anauê")')->count() > 0);
	}

}
