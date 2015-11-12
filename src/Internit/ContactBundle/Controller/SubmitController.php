<?php
namespace Internit\ContactBundle\Controller;

use Tupi\ContentBundle\Controller\PageController;
use Internit\ContactBundle\Form\ContactType;
use Internit\ContactBundle\Entity\ContactRequest;
use Internit\ContactBundle\Entity\ContactPerson;
use Internit\ContactBundle\Service\MailService;
use Internit\SiteBaseBundle\Controller\BaseController;

use Tupi\ContentBundle\Entity\LinkContent;

use Tupi\AdminBundle\Types\StatusType;

use Symfony\Component\Routing\RequestContext;
use Tupi\ContentBundle\Entity\Page;
use Internit\ContactBundle\Entity\Trabalhe;
use Internit\ContactBundle\Form\TrabalheType;

class SubmitController extends BaseController
{
    public function perguntaAction() 
    {
    	$page = new Page();
    	//pegando host do servidor
    	$host = $this->container->get('router')->getContext()->getHost();
    	//criando host do e-mail
    	$host_email = 'no-reply@'.str_replace('www.', "", $host);
    	
        $contactRequest = new ContactRequest();

        $contactRequest->setPerson(new ContactPerson());
        
        $form = $this->createForm(new ContactType($this->getDoctrine()->getRepository('InternitContactBundle:ContactSubject')), $contactRequest);
        $errors = array();
        $success = "";
        if ($this->getRequest()->isMethod('POST') && !$form->isValid()) 
        {
            $errors = $this->get('validator')->validate($form);
        } 
        else if ($this->getRequest()->isMethod('POST') && $form->isValid()) 
        {
        	//identificando o assunto
        	$subject = $contactRequest->getSubject()->getTitle();
        	$subjectId = $contactRequest->getSubject()->getId();

        	
        	//pegando grupos para qual a mensagem será enviada
        	$groups = $contactRequest->getSubject()->getGroups();
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
        	$randomMode = $contactRequest->getSubject()->getRandomStatus();        	
        	//se sim faz com que os grupos de emails se randomizem
        	if($randomMode == StatusType::ACTIVE)
        	{
        		//verificando para qual grupo de e-mail foi enviado a última requisição
				$lastGroupSelected = $this->getDoctrine()->getRepository('InternitContactBundle:ContactRequest')->lastGroupSelected($subjectId);
        		//listando todos os grupos que petencem a este grupo
				$listAllGroupsThisSubject = $this->getDoctrine()->getRepository('InternitContactBundle:ContactRequest')->listAllGroupsThisSubject($subjectId);        		
				
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
        		$emails = $this->getDoctrine()->getRepository('InternitContactBundle:ContactRequest')->listEmailsThisGroup($textGroups);
        		$selectedEmails = $this->array_value_recursive('email', $emails);
        	}

        	//setando grupo de emails e emails para tabela contact_request
        	if($selectedEmails>1)
        	{
        		$textEmails = implode($selectedEmails, ';');
        	} else {
        		$textEmails = $selectedEmails;
        	}
        	$contactRequest->setSentToGroup($textGroups);
        	$contactRequest->setSentToEmails($textEmails);        	
        	
        	//salvando request
            $contactRepo = $this->getDoctrine()->getRepository('InternitContactBundle:ContactRequest');
            $contactRepo->create($form->getData());            
            
            //disparo da mensagem para usuário
            $contactRequest = $form->getData();
            
            $message = $this->get('internit.contact.mail.service')->createMessage();
            $message->setSubject('[Contato Elo Empreendimentos] – Efetuado com Sucesso')
            ->setFrom($host_email, $contactRequest->getPerson()->getName())
            ->setTo($contactRequest->getPerson()->getEmail(), $contactRequest->getPerson()->getName())
            ->setBody(
                $this->renderView(
                	'InternitContactBundle:Email:Person\email.html.twig',
                    	array(	'nome' => $contactRequest->getPerson()->getName(),
                    			'number' => $contactRequest->getId(),
                    	 		'url_site' => $host)
                ), 'text/html'
            );
            
            $this->get('internit.contact.mail.service')->send($message);
            
            //disparo de mensagem para administrador
            $message = $this->get('internit.contact.mail.service')->createMessage();
            $message->setSubject('CONTATO - ['.$subject.']')
            ->setFrom($host_email)
            ->setTo($selectedEmails)
            ->setBody(
                $this->renderView(
                'InternitContactBundle:Email:Admin\email.html.twig',
                    array('nome' => $contactRequest->getPerson()->getName(), 
                          'email' => $contactRequest->getPerson()->getEmail(),
                          'telefone' => $contactRequest->getPerson()->getTelefone(),
                          'celular' => $contactRequest->getPerson()->getCelular(),
                          'mensagem' => $contactRequest->getMessage(),
                    	  'informativo' => $contactRequest->getInformativo(),
                    	  'conheceu' => $contactRequest->getConheceu(),
                    )
                ), 'text/html'
            );
            
            $this->get('internit.contact.mail.service')->send($message);
            
            $success = "Sua mensagem foi enviada com sucesso. <br/> Em breve estaremos te retornando.";
        }
        
        return $this->render('InternitSiteBaseBundle:Default:contato.html.twig', array(
            'session' => $page,
            'form' => $form->createView(),
            'errors' => $errors,
            'success' => $success,
        ));
    }
    public function trabalheAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$page = new Page();
    	$errors = array();
    	$success = "";
    	 
    	$trabalhe = new Trabalhe();
    	$trabalhe->setPerson(new ContactPerson());
    	 
    	$form = $this->createForm(new TrabalheType($this->getDoctrine()->getRepository('InternitContactBundle:ContactSubject')), $trabalhe);
    	 
    	 
    	if ($this->getRequest()->isMethod('POST') && !$form->isValid())
    	{
    		$errors = $this->get('validator')->validate($form);
    	}
    	else if ($this->getRequest()->isMethod('POST') && $form->isValid())
    	{
    		/* $file = $form['imagem']['imagem']->getData();
    		  
    		if(!empty($file))
    		{
    		$nameImage = "imagem_".date("U").".".$file->guessExtension();
    		$arquivo = $estabelecimento->getImagem();
    		$upload = new UploadHelper();
    		$arquivo->setTitle($nameImage);
    		$arquivo->setLabel($nameImage);
    		$upload->setMedia($arquivo);
    		$upload->setFile($file);
    		$upload->doUpload();
    		$upload->thumbnail(252, 266);
    		$estabelecimento->setImagem($upload->getMedia());
    		} */
    		 
    		 
    		//$request = $this->getRequest();
    		//$estab = $request->get('estabelecimento_estabelecimento');
    		 
    		$em->persist($trabalhe);
    		$em->flush();
    		 
    		$success = "Cadastrado com sucesso!";
    		 
    		//$emails = $estabelecimento->getEmails();
    		$data = date("d/m/Y H:i:s");
    		 
    		/* if($emails->count() > 0){
    		 $email = array(
    		 'portaldoseuprazer@gmail.com'=> "Portal do Seu Prazer – Anuncie Estabelecimento|Cadastro de profissional realizado em {$data} no Portal do Seu Prazer <br> Dados do Anunciante <br> Nome: {$estabelecimento->getNome()}<br>Telefone: {$estabelecimento->getTelefones()->get(0)->getTelefone()}<br>Email: {$emails[0]->getEmail()}<br><br>Para ver todas as informações acesso o painel administração do Portal.",
    		 'contato@portaldoseuprazer.com'=> "Portal do Seu Prazer – Anuncie Estabelecimento|Cadastro de profissional realizado em {$data} no Portal do Seu Prazer <br> Dados do Anunciante <br> Nome: {$estabelecimento->getNome()}<br>Telefone: {$estabelecimento->getTelefones()->get(0)->getTelefone()}<br>Email: {$emails[0]->getEmail()}<br><br>Para ver todas as informações acesso o painel administração do Portal.",
    		 $emails[0]->getEmail() => 'Portal do Seu Prazer – Cadastro Realizado|O cadastro do seu estabelecimento foi realizado com sucesso.<br>Estamos Analisando as informações e em breve retornaremos o contato para publicação do seu anúncio.'
    		 );
    
    		 foreach ($email as $mail=>$msg)
    		 {
    		 $dados = explode("|", $msg);
    		 $message = $this->get('admin.mail.service')->createMessage();
    		 $message->setSubject($dados[0])
    		 ->setFrom('contato@portaldoseuprazer.com')
    		 ->setTo($mail, $estabelecimento->getNome())
    		 ->setBody(
    		 $this->renderView('InternitAssinanteBundle:TemplateEmail:cadastro.txt.twig', array(
    		 'msg' => $dados[1],
    		 )
    		 ),'text/html');
    		  
    		 $this->get('admin.mail.service')->send($message);
    		 }
    		} */
    		 
    
    	}
    	return $this->render('InternitContactBundle:Trabalhe:trabalhe-conosco.html.twig', array(
    			'session' => new Page(),
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