<?php
namespace Internit\ContactBundle\Controller;

use Tupi\AdminBundle\Controller\CrudController;
use Tupi\AdminBundle\Controller\ReturnVal;
use Doctrine\ORM\EntityManager;
use Internit\ContactBundle\Form\ResponseType;
use Internit\ContactBundle\Entity\ContactResponse;

use Symfony\Component\Routing\RequestContext;

class ContactController extends CrudController
{
    const REPOSITORY_NAME = 'InternitContactBundle:ContactRequest';
    
    protected $repositoryName = self::REPOSITORY_NAME;
    
    protected $bundleName = 'InternitContactBundle:Contact';
    
    protected $defaultRoute = 'tupi_contact_home';
    
protected function createTypedForm($type)
    {
        return $this->createForm(new ResponseType(), $type);
    }
    
    protected function initObject($id = null) 
    {
        $contactRequest = $this->getRepository()->findOneBy(array('id' => $id));
        
        $contactResponse = new ContactResponse();
        $contactResponse->setRequest($contactRequest);
        
        if($contactRequest->getResponses()->count() > 0) {
            $contactResponse = $contactRequest->getResponses()->last();
        }
        
        return $contactResponse;
    }
    
    protected function save(ReturnVal $return, $id = null, $obj, $form, EntityManager $em)
    {
    	//pegando host do servidor
    	$host = $this->container->get('router')->getContext()->getHost();
    	//criando host do e-mail
    	$host_email = 'no-reply@'.str_replace('www.', "", $host);
    	
        $obj->setCreatedAt(new \DateTime());
        $obj->getRequest()->setUpdatedAt($obj->getCreatedAt());
        $em->merge($obj);
        $return->setMessage("Contato respondido com sucesso!");
        
        //disparo da mensagem
        $message = $this->get('internit.contact.mail.service')->createMessage();
        $message->setSubject('RES: Contato do site')
        ->setFrom($host_email)
        ->setTo($obj->getRequest()->getPerson()->getEmail(), $obj->getRequest()->getPerson()->getName())
        ->setBody(
            $this->renderView(
                'InternitContactBundle:Email:Person\email-resposta.txt.twig',
                array(
                    'nome' => $obj->getRequest()->getPerson()->getName(), 
                    'answerMessage' => $obj->getMessage(),
                    'sourceMessage' => $obj->getRequest()->getMessage(),
                	'url_site' => $host
                )
            ), 'text/html'
        );
        
        $this->get('internit.contact.mail.service')->send($message);
    }
}