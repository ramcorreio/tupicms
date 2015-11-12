<?php
namespace Internit\NewsletterBundle\Controller;

use Internit\ContactBundle\Service\MailService;
use Internit\SiteBaseBundle\Controller\BaseController;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use Tupi\ContentBundle\Entity\LinkContent;
use Tupi\AdminBundle\Types\StatusType;

use Doctrine\ORM\EntityManager;

use Internit\NewsletterBundle\Entity\Newsletter;
use Internit\NewsletterBundle\Form\NewsletterType;

class SubmitController extends BaseController
{
    public function newsletterAction(Request $request) 
    {    	
    	//pagina do newsletter
    	$page = $request->headers->get('referer');
		//dados do newsletter
		$email = $request->query->get('email');
		$name = $request->query->get('name');
		$telefone = $request->query->get('telefone');
		$celular = $request->query->get('celular');
		if($name == null){ $name = null; }
		//pegando repositório
		$repositorio = $this->getDoctrine()->getRepository('InternitNewsletterBundle:Newsletter');
		
		$listaEmails = $repositorio->findBy(array('email' => $email));
		if(empty($listaEmails))
		{
			$newsletter = new Newsletter();
			$newsletter->setEmail($email);
			$newsletter->setName($name);
			$newsletter->setTelefone($telefone);
			$newsletter->setCelular($celular);
			$repositorio->create($newsletter);
			
	    	return new Response('OK|Cadastrado com sucesso.');
		} else {
			return new Response('erro|O e-mail "'.$email.'" já está cadastrado.');
		}
    	 
    }
    
}