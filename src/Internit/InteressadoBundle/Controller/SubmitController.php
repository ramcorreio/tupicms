<?php
namespace Internit\InteressadoBundle\Controller;

use Tupi\ContentBundle\Controller\PageController;
use Internit\ContactBundle\Entity\ContactPerson;
use Internit\ContactBundle\Service\MailService;
use Internit\SiteBaseBundle\Controller\BaseController;

use Tupi\ContentBundle\Entity\LinkContent;

use Tupi\AdminBundle\Types\StatusType;

use Symfony\Component\Routing\RequestContext;
use Tupi\ContentBundle\Entity\Page;
use Internit\InteressadoBundle\Entity\Interessado;
use Internit\InteressadoBundle\Form\InteressadoType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Request;

class SubmitController extends BaseController
{
	public function interessadoAction($imovelId)
    {
    	$em = $this->getDoctrine()->getManager();
    	$page = new Page();
    	//pegando host do servidor
   
    	$host = $this->container->get('router')->getContext()->getHost();
    	//criando host do e-mail
    	$host_email = 'no-reply@'.str_replace('www.', "", $host);
    	 
    	$interessado = new Interessado();
    	$interessado->setPerson(new ContactPerson());
    	$form = $this->createForm(new InteressadoType($this->getDoctrine()->getRepository('InternitInteressadoBundle:InteressadoSubject') ,$em, $imovelId), $interessado);
    	
    	################################################################
    	#	Menssagem incluida como vazia manualmente, pois o projeto  #
    	#	Elo Empreendimentos não necessita desse campo, caso seja   #
    	#	necessário, remover o $interessado->setMessage("");        #
    	################################################################
    	
    	$interessado->setMessage("");
    	
    	
    	$errors = array();
    	$success = "";
    	if ($this->getRequest()->isMethod('POST') && !$form->isValid())
    	{
    		$errors = $this->get('validator')->validate($form);
    	}
    	else if ($this->getRequest()->isMethod('POST') && $form->isValid())
    	{
    		
    		//identificando o assunto
    		$subject = $interessado->getSubject()->getTitle();
    		$subjectId = $interessado->getSubject()->getId();
    
    		 
    		//pegando grupos para qual a mensagem será enviada
    		$groups = $interessado->getSubject()->getGroups();
    		$selectedGroups = array();
    		$selectedEmails = array();
    		
    		foreach ($groups as $group)
    		{
    			$selectedGroups[] = $group->getName();
    			$emails = $group->getEmails();
    			foreach ($emails as $email)
    			{
    				$selectedEmails[] = $email->getEmail();
    			}
    		}
    		 
    		$textGroups = implode($selectedGroups, ';');
    
    		//verfica se o modo randomico está ativo
    		$randomMode = $interessado->getSubject()->getRandomStatus();
    		//se sim faz com que os grupos de emails se randomizem
    		if($randomMode == StatusType::ACTIVE)
    		{
    			//verificando para qual grupo de e-mail foi enviado a última requisição
    			$lastGroupSelected = $this->getDoctrine()->getRepository('InternitInteressadoBundle:Interessado')->lastGroupSelected($subjectId);
    			//listando todos os grupos que petencem a este grupo
    			$listAllGroupsThisSubject = $this->getDoctrine()->getRepository('InternitInteressadoBundle:Interessado')->listAllGroupsThisSubject($subjectId);
    
    			//pegando a última posição da lista de grupos para este assunto
    			end($listAllGroupsThisSubject);
    			$lastPositionRandom = key($listAllGroupsThisSubject);
    			//pega a posição do grupo para qual foi enviado a última requisição
    			$positionRandom = $this->recursive_array_search($lastGroupSelected, $listAllGroupsThisSubject);
    
    			//verifica se a posição na qual o grupo de e-mail está lista é a última
    			//se for a última ele retorna para posição 0 (volta para início)
    			//se não ele adiciona a posição do último grupo de e-mail enviado
    			if($positionRandom >= $lastPositionRandom)
    			{
    				$realPosition = 0;
    			} else {
    				$realPosition = $positionRandom+1;
    			}
    
    			//salvando nome do grupo para qual foi enviado o e-mail
    			$textGroups = $listAllGroupsThisSubject[$realPosition]['name'];
    			//listando emails do grupo da vez
    			$emails = $this->getDoctrine()->getRepository('InternitInteressadoBundle:Interessado')->listEmailsThisGroup($textGroups);
    			$selectedEmails = $this->array_value_recursive('email', $emails);
    		}
    		
    		//setando grupo de emails e emails para tabela contact_request
    		if($selectedEmails>1)
    		{
    			$textEmails = implode($selectedEmails, ';');
    		} else {
    			$textEmails = $selectedEmails;
    		}
    		$interessado->setSentToGroup($textGroups);
    		$interessado->setSentToEmails($textEmails);
    		
    		//salvando request
    		$contactRepo = $this->getDoctrine()->getRepository('InternitInteressadoBundle:Interessado');
    		$contactRepo->create($form->getData());
    		
    		//disparo da mensagem para usuário
    		$interessado = $form->getData();
    
    		$message = $this->get('internit.contact.mail.service')->createMessage();
    		
    		$message->setSubject('[Elo Empreendimentos] – '. $interessado->getImovel()->getName())
    		->setFrom($host_email)
    		->setTo($interessado->getPerson()->getEmail(), $interessado->getPerson()->getName())
    		->setBody(
    				$this->renderView(
    						'InternitInteressadoBundle:Email:Person\emailInteressado.html.twig',
    						array(	'nome' => $interessado->getPerson()->getName(),
    								'number' => $interessado->getId(),
    								'url_site' => $host,
    						        'imovel' => $interessado->getImovel()
    						)
    				), 'text/html'
    		);
    
    		$this->get('internit.contact.mail.service')->send($message);
    		
    		
    		//disparo de mensagem para administrador
    		$message = $this->get('internit.contact.mail.service')->createMessage();
    		$message->setSubject('[Interesse de Compra] - ['.$subject.']')
    		->setFrom($host_email)
    		->setTo($selectedEmails)
    		->setBody(
    				$this->renderView(
    						'InternitInteressadoBundle:Email:Admin\email_interessado.html.twig',
    						array(
    								'nome' => $interessado->getPerson()->getName(),
    								'email' => $interessado->getPerson()->getEmail(),
    								'telefone' => $interessado->getPerson()->getTelefone(),
    								'celular' => $interessado->getPerson()->getTelefone(),
    						        'imovel' => $interessado->getImovel(),
    						        'message' => $interessado->getMessage(),
    						)
    				), 'text/html'
    		);
    
    		$this->get('internit.contact.mail.service')->send($message);
    
    		$success = "Seu interesse neste imóvel foi registrado com sucesso. Em breve estaremos te retornando.";
    	}
    	return $this->render('InternitSiteBaseBundle:Default:interessado.html.twig', array(
    			'session' => $page,
    			'form' => $form->createView(),
    			'errors' => $errors,
    			'success' => $success,
    	));
    }
    
    private function recursive_array_search($needle,$haystack) {
    	foreach($haystack as $key=>$value) {
    		$current_key=$key;
    		if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
    			return $current_key;
    		}
    	}
    	return false;
    }
    
    private function array_value_recursive($key, array $arr)
    {
	    $val = array();
	    array_walk_recursive($arr, function($v, $k) use($key, &$val){
	        if($k == $key) array_push($val, $v);
	    });
	    return count($val) > 1 ? $val : array_pop($val);
	}
	
	
    
}