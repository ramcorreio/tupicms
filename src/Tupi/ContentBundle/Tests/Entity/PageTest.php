<?php
namespace Tupi\ContentBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tupi\ContentBundle\Entity\Page;
use Tupi\ContentBundle\Types\PageStatusType;
use Tupi\ContentBundle\Entity\MediaFile;
use Tupi\AdminBundle\Commom\Base64;
use Symfony\Component\HttpFoundation\File\File;
use Tupi\AdminBundle\Types\StatusType;
use Tupi\ContentBundle\Entity\Base64String;
use Tupi\ContentBundle\Controller\PageController;
use Tupi\ContentBundle\Controller\MediaController;

class PageTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    private function getRepository()
    {
    	return $this->em->getRepository(PageController::REPOSITORY_NAME);
    }
    
    public function testCheckRepo()
    {
    	$repo = $this->getRepository();
    	$this->assertNotNull($repo);
    }
    
    public function testCreatePage()
    {
    	$initPages = $this->getRepository()->findAll();
    	
    	$page = new Page();
    	$page->setTitle("Bem vindo " . uniqid());
    	$page->setSummary("Seja bem vindo sempre!!");
    	$page->setCreatedAt(new \DateTime());
    	$page->setUpdatedAt(new \DateTime());
    	$page->setStatus(PageStatusType::DRAFT);
    	$page->setBody("<p>Testando minha página nova.</p>");
    	
    	$this->em->persist($page);
    	$this->em->flush();
    	 
    	$pages = $this->getRepository()->findAll();
    	$this->assertCount(sizeof($initPages) + 1, $pages);
    	
    	return $page->getId();
    }
    
    public function testUpdatePage()
    {
    	$initPages = $this->getRepository()->findAll();
    	
    	$page = new Page();
    	$page->setTitle("Bem vindo na página de testes(" . uniqid() . ")");
    	$page->setSummary("Seja bem vindo sempre!!");
    	$page->setCreatedAt(new \DateTime());
    	$page->setUpdatedAt(new \DateTime());
    	$page->setStatus(PageStatusType::DRAFT);
    	$page->setBody("<p>Testando minha página nova.</p>");
    	$page->setHeader("Meu cabeçalho");
    	
    	$page->getCreatedAt()->setDate(2013, 1, 10);
    	$this->em->persist($page);
    	$this->em->flush();
    
    	$pages = $this->getRepository()->findAll();
    	$this->assertCount(sizeof($initPages) + 1, $pages);
    	$this->assertNotNull($page->getId());
    	
    	$dbPage = $this->getRepository()->findOneBy(array('id' => $page->getId()));
    	
    	$newTitle = 'Minha página nova!!!! '. uniqid();
    	$dbPage->setTitle($newTitle);
    	$dbPage->setUpdatedAt(new \DateTime());
    	$this->em->merge($dbPage);
    	$this->em->flush();
    	
    	$dbPage = $this->getRepository()->findOneBy(array('id' => $page->getId()));
    	
    	$this->assertEquals($dbPage->getTitle(), $newTitle);
    }
    
    
	/**
     * @depends testCreatePage
     */
    public function testCreatePageWithImage($id)
    {
    	
    	$media = $this->createImage();
    	
    	$page = $this->getRepository()->findOneBy(array('id' => $id));
    	$page->getImages()->add($media);
    	$page->setUpdatedAt(new \DateTime());
    	 
    	$this->em->merge($page);
    	$this->em->flush();
    
    	$page = $this->getRepository()->findOneBy(array('id' => $id));
    	$this->assertCount(1, $page->getImages());
    }
    
    private function createImage() 
    {
    	$iniFiles = $this->em->getRepository(MediaController::REPOSITORY_NAME)->findAll();
    	
    	$media = new MediaFile();
    	$media->setTitle("imagem associada " . uniqid());
    	$media->setSummary("resumo " . $media->getTitle());
    	$media->setLabel("label " . $media->getTitle());
    	$media->setCreatedAt(new \DateTime());
    	$media->setUpdatedAt(new \DateTime());
    	$media->setSource("source " . $media->getTitle());
    	$media->setStatus(StatusType::ACTIVE);
    	
    	$media->setMimeType('image/jpeg');
    	$media->getBin()->setValue(Base64::encodeFile(new File(__DIR__ . '/Fixtures/DSC00121.JPG')));
    	
    	$this->em->persist($media);
    	$this->em->flush();
    		 
    	$files = $this->em->getRepository(MediaController::REPOSITORY_NAME)->findAll();
    	$this->assertCount(sizeof($iniFiles) + 1, $files);
    	$this->assertEquals("resumo " . $media->getTitle(), $media->getSummary());
    	$this->assertEquals("label " . $media->getTitle(), $media->getLabel());
    	$this->assertEquals("source " . $media->getTitle(), $media->getSource());
    	
    	return $media;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
    	parent::tearDown();
    	$this->em->close();
    }
}
