<?php
namespace Internit\TrabalheBundle\Controller;

use Tupi\ContentBundle\Controller\PageController;
use Internit\ContactBundle\Entity\ContactPerson;
use Internit\ContactBundle\Service\MailService;
use Internit\SiteBaseBundle\Controller\BaseController;

use Tupi\ContentBundle\Entity\LinkContent;

use Tupi\AdminBundle\Types\StatusType;

use Symfony\Component\Routing\RequestContext;
use Tupi\ContentBundle\Entity\Page;
use Internit\TrabalheBundle\Entity\Trabalhe;
use Internit\TrabalheBundle\Form\TrabalheType;
use Internit\TrabalheBundle\Form\UploadHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Request;
use Internit\TrabalheBundle\Entity\Cidade;
use Internit\TrabalheBundle\Entity\Estado;

class SubmitController extends BaseController
{
	public function trabalheAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$page = new Page();
    	//pegando host do servidor
   
    	$host = $this->container->get('router')->getContext()->getHost();
    	//criando host do e-mail
    	$host_email = 'no-reply@'.str_replace('www.', "", $host);
    	 
    	$trabalhe = new Trabalhe();
    	$trabalhe->setPerson(new ContactPerson());
    	$trabalheEstado = new Estado();
    	$trabalheCidade = new Cidade();
    	$trabalheCidade->setEstado($trabalheEstado);
    	$trabalhe->setCidade($trabalheEstado);

    	
    	$form = $this->createForm(new TrabalheType($this->getDoctrine()->getRepository('InternitTrabalheBundle:TrabalheSubject'),$em), $trabalhe);
    	
    	$errors = array();
    	$success = "";
    	if ($this->getRequest()->isMethod('POST') && !$form->isValid())
    	{
    		$errors = $this->get('validator')->validate($form);
    	}
    	else if ($this->getRequest()->isMethod('POST') && $form->isValid())
    	{

    		//identificando o assunto
    		$subject = $trabalhe->getSubject()->getTitle();
    		$subjectId = $trabalhe->getSubject()->getId();
    
    		 
    		//pegando grupos para qual a mensagem será enviada
    		$groups = $trabalhe->getSubject()->getGroups();
    		$selectedGroups = array();
    		$selectedEmails = array();
    		
    		$file = $form['curriculo']['file']->getData();
    		
    		if(!empty($file))
    		{
    			$arquivo = $trabalhe->getCurriculo();
    			$arquivo->setTitle("documento_".date("U").".".$file->guessExtension());
    			$arquivo->setLabel("documento");
    		
    			$upload = new UploadHelper($this->container);
    			$upload->setMedia($arquivo);
    			$upload->setFile($file);
    			$upload->doUpload();
    			$trabalhe->setCurriculo($upload->getMedia());
    		}
    		
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
    		$randomMode = $trabalhe->getSubject()->getRandomStatus();
    		//se sim faz com que os grupos de emails se randomizem
    		if($randomMode == StatusType::ACTIVE)
    		{
    			//verificando para qual grupo de e-mail foi enviado a última requisição
    			$lastGroupSelected = $this->getDoctrine()->getRepository('InternitTrabalheBundle:Trabalhe')->lastGroupSelected($subjectId);
    			//listando todos os grupos que petencem a este grupo
    			$listAllGroupsThisSubject = $this->getDoctrine()->getRepository('InternitTrabalheBundle:Trabalhe')->listAllGroupsThisSubject($subjectId);
    
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
    			$emails = $this->getDoctrine()->getRepository('InternitTrabalheBundle:Trabalhe')->listEmailsThisGroup($textGroups);
    			$selectedEmails = $this->array_value_recursive('email', $emails);
    		}
    		
    		//setando grupo de emails e emails para tabela contact_request
    		if($selectedEmails>1)
    		{
    			$textEmails = implode($selectedEmails, ';');
    		} else {
    			$textEmails = $selectedEmails;
    		}
    		$trabalhe->setSentToGroup($textGroups);
    		$trabalhe->setSentToEmails($textEmails);
    		
    		//salvando request
    		$contactRepo = $this->getDoctrine()->getRepository('InternitTrabalheBundle:Trabalhe');
    		$contactRepo->create($form->getData());
    		
    		//disparo da mensagem para usuário
    		$trabalhe = $form->getData();

    		$message = $this->get('internit.contact.mail.service')->createMessage();
    		
    		$message->setSubject('[Cadastro de Currículo] – Efetuado com Sucesso.')
    		->setFrom($host_email)
    		->setTo($trabalhe->getPerson()->getEmail(), $trabalhe->getPerson()->getName())
    		->setBody(
    				$this->renderView(
    						'InternitTrabalheBundle:Email:Person\emailTrabalhe.html.twig',
    						array(	'nome' => $trabalhe->getPerson()->getName(),
    								'number' => $trabalhe->getId(),
    								'url_site' => $host)
    				), 'text/html'
    		);
    
    		$this->get('internit.contact.mail.service')->send($message);
    		
    		//Preparando Arquivo para ser enviado como anexo
    		$path = sys_get_temp_dir();
    		$caminho = $path.'/'.$trabalhe->getCurriculo()->getTitle();    		
    		$anexo = fopen($caminho,'wb');
    		fwrite($anexo, base64_decode($trabalhe->getCurriculo()->getBin()->getValue()));
    		fclose($anexo);
    		
    		//disparo de mensagem para administrador
    		$message = $this->get('internit.contact.mail.service')->createMessage();
    		$message->setSubject('[Currículo] - ['.$subject.']')
    		->setFrom($host_email)
    		->setTo($selectedEmails)
    		->setAttach($caminho)
    		->setBody(
    				$this->renderView(
    						'InternitTrabalheBundle:Email:Admin\email_trabalhe.html.twig',
    						array(
    								'assunto' => $trabalhe->getSubject()->getTitle(),
    								'conheceu' => $trabalhe->getConheceu(),
    								'nome' => $trabalhe->getPerson()->getName(),
    								'nascimento' => $trabalhe->getPerson()->getNascimento(),
    								'sexo' => $trabalhe->getSexo(),
    								'email' => $trabalhe->getPerson()->getEmail(),
    								'telefone' => $trabalhe->getPerson()->getTelefone(),
    								'celular' => $trabalhe->getPerson()->getTelefone(),
    								'escolaridade' => $trabalhe->getEscolaridade(),
    								'faixa_salarial' => $trabalhe->getFaixaSalarial(),
    								'facebook' => $trabalhe->getFacebook(),
    								'linkedin' => $trabalhe->getLinkedin(),
    								'informativo' => $trabalhe->getInformativo(),
    								'cidade' => $trabalhe->getCidade(),
    								'bairro' => $trabalhe->getBairro(),
    								'estado' => $trabalhe->getCidade()->getEstado()->getNome(),
   						)
    				), 'text/html'
    		);
    
    		$this->get('internit.contact.mail.service')->send($message);
    
    		$success = "Currículo enviado com sucesso. <br/> Assim que precisarmos de algum profissional com o seu perfil, entraremos em contato.";
    	}
    
    	return $this->render('InternitSiteBaseBundle:Default:trabalhe-conosco.html.twig', array(
    			'session' => $page,
    			'form' => $form->createView(),
    			'errors' => $errors,
    			'success' => $success,
    	));
    }
    
    public function cidadesAction()
    {
    	$request = $this->get('request');
    	$estado_id = $request->query->get('estado_id');
    	$estados = $this->getDoctrine()->getRepository('InternitTrabalheBundle:Estado')->find($estado_id);
    	 
    	$json = array();
    	 
    	foreach ($estados->getCidades() as $cidade)
    	{
    		$json[$cidade->getId()] = $cidade->getNome();
    	}
    	 
    	$response = new JsonResponse();
    	$response->setContent(json_encode($json));
    	return $response;
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