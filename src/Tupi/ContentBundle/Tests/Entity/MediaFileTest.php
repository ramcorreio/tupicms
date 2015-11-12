<?php
namespace Tupi\ContentBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tupi\ContentBundle\Entity\Base64String;
use Tupi\AdminBundle\Commom\Base64;
use Symfony\Component\HttpFoundation\File\File;
use Tupi\ContentBundle\Entity\MediaFile;
use Tupi\AdminBundle\Types\StatusType;

class MediaFileTest extends WebTestCase
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
    	return $this->em->getRepository('TupiContentBundle:MediaFile');
    }
    
    public function testCheckRepo()
    {
    	$media = $this->getRepository();
    	$this->assertNotNull($media);
    }
    
    private function loadImage(MediaFile $media) 
    {
        $media->setStatus(StatusType::ACTIVE);
    	$media->setMimeType('image/jpeg');
    	$media->getBin()->setValue(Base64::encodeFile(new File(__DIR__ . '/Fixtures/DSC00121.JPG')));
    }
    
    public function testRemoveOneMoreMedia()
    {
        
    }
    
    private function createImage()
    {
        $iniFiles = $this->getRepository()->findAll();
         
        $media = new MediaFile();
        $media->setTitle("imagem gerada em " . date('d/M/y h:m:s'));
        $media->setSummary("resumo " . $media->getTitle());
        $media->setLabel("label " . $media->getTitle());
        $media->setCreatedAt(new \DateTime());
        $media->setUpdatedAt(new \DateTime());
        $media->setSource("source " . $media->getTitle());
        $this->loadImage($media);
         
        $this->em->persist($media);
        $this->em->flush();
         
        $files = $this->getRepository()->findAll();
        $this->assertCount(sizeof($iniFiles) + 1, $files);
        $this->assertEquals("resumo " . $media->getTitle(), $media->getSummary());
        $this->assertEquals("label " . $media->getTitle(), $media->getLabel());
        $this->assertEquals("source " . $media->getTitle(), $media->getSource());
         
        return $media;
    }
    
    public function testCreateMedia()
    {
        $iniFiles = $this->getRepository()->findAll();
        
        $images = array();
        for ($i = 0; $i < 5; $i++) {
            $media = $this->createImage();
            array_push($images, $media->getId());
        }
    	
    	$files = $this->getRepository()->findAll();
    	$this->assertCount(sizeof($iniFiles) + sizeof($images), $files);
    	
    	$qb = $this->getRepository()->createQueryBuilder("item");
    	$qb->delete()->where($qb->expr()->in('item.id', $images));
    	
    	$this->assertEquals(5, $qb->getQuery()->getSingleScalarResult());
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
