<?php
namespace Internit\OferecaBundle\Controller;

use Tupi\ContentBundle\Controller\PageController;
use Internit\ContactBundle\Entity\ContactPerson;
use Internit\ContactBundle\Service\MailService;
use Internit\SiteBaseBundle\Controller\BaseController;

use Tupi\ContentBundle\Entity\LinkContent;

use Tupi\AdminBundle\Types\StatusType;

use Symfony\Component\Routing\RequestContext;
use Tupi\ContentBundle\Entity\Page;
use Internit\OferecaBundle\Entity\Ofereca;
use Internit\OferecaBundle\Form\OferecaType;
use Internit\OferecaBundle\Form\UploadHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\BrowserKit\Request;
use Tupi\ContentBundle\Entity\ImageMedia;
use Internit\OferecaBundle\Classe\phpQuery;
use Internit\OferecaBundle\Classe\DOMEvent;

class SubmitController extends BaseController
{
	public function OferecaAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$page = new Page();
    	//pegando host do servidor
   
    	$host = $this->container->get('router')->getContext()->getHost();
    	//criando host do e-mail
    	$host_email = 'no-reply@'.str_replace('www.', "", $host);
    	 
    	$ofereca = new Ofereca();
    	$ofereca->setPerson(new ContactPerson());
    	$form = $this->createForm(new OferecaType($this->getDoctrine()->getRepository('InternitOferecaBundle:OferecaSubject'),$em), $ofereca);
    	
    	$errors = array();
    	$success = "";
    	if ($this->getRequest()->isMethod('POST') && !$form->isValid())
    	{
    		$errors = $this->get('validator')->validate($form);
    	}
    	else if ($this->getRequest()->isMethod('POST') && $form->isValid())
    	{
    		
    		//identificando o assunto
    		$subject = $ofereca->getSubject()->getTitle();
    		$subjectId = $ofereca->getSubject()->getId();
    
    		 
    		//pegando grupos para qual a mensagem será enviada
    		$groups = $ofereca->getSubject()->getGroups();
    		$selectedGroups = array();
    		$selectedEmails = array();
    		
    		/* $file = $form['curriculo']['file']->getData();
    		
    		if(!empty($file))
    		{
    			$arquivo = $ofereca->getCurriculo();
    			$arquivo->setTitle("documento_".date("U").".".$file->guessExtension());
    			$arquivo->setLabel("documento");
    		
    			$upload = new UploadHelper($this->container);
    			$upload->setMedia($arquivo);
    			$upload->setFile($file);
    			$upload->doUpload();
    			$ofereca->setCurriculo($upload->getMedia());
    		} */
    		
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
    		$randomMode = $ofereca->getSubject()->getRandomStatus();
    		//se sim faz com que os grupos de emails se randomizem
    		if($randomMode == StatusType::ACTIVE)
    		{
    			//verificando para qual grupo de e-mail foi enviado a última requisição
    			$lastGroupSelected = $this->getDoctrine()->getRepository('InternitOferecaBundle:Ofereca')->lastGroupSelected($subjectId);
    			//listando todos os grupos que petencem a este grupo
    			$listAllGroupsThisSubject = $this->getDoctrine()->getRepository('InternitOferecaBundle:Ofereca')->listAllGroupsThisSubject($subjectId);
    
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
    			$emails = $this->getDoctrine()->getRepository('InternitOferecaBundle:Ofereca')->listEmailsThisGroup($textGroups);
    			$selectedEmails = $this->array_value_recursive('email', $emails);
    		}
    		
    		//setando grupo de emails e emails para tabela contact_request
    		if($selectedEmails>1)
    		{
    			$textEmails = implode($selectedEmails, ';');
    		} else {
    			$textEmails = $selectedEmails;
    		}
    		$ofereca->setSentToGroup($textGroups);
    		$ofereca->setSentToEmails($textEmails);
    		
    		//salvando request
    		$contactRepo = $this->getDoctrine()->getRepository('InternitOferecaBundle:Ofereca');
    		$contactRepo->create($form->getData());
    		
    		//disparo da mensagem para usuário
    		$ofereca = $form->getData();
    
    		$message = $this->get('internit.contact.mail.service')->createMessage();
    		
    		$message->setSubject('[Oferta de Terreno] – Cadastro efetuado com Sucesso.')
    		->setFrom($host_email)
    		->setTo($ofereca->getPerson()->getEmail(), $ofereca->getPerson()->getName())
    		->setBody(
    				$this->renderView(
    						'InternitOferecaBundle:Email:Person\emailOfereca.html.twig',
    						array(	'nome' => $ofereca->getPerson()->getName(),
    								'number' => $ofereca->getId(),
    								'url_site' => $host)
    				), 'text/html'
    		);
    
    		$this->get('internit.contact.mail.service')->send($message);
    		
    		//disparo de mensagem para administrador
    		$message = $this->get('internit.contact.mail.service')->createMessage();
    		$message->setSubject('[Ofereça Seu Terreno] – Oferta Cadastrada.')
    		->setFrom($host_email)
    		->setTo($selectedEmails)
    		->setBody(
    				$this->renderView(
    						'InternitOferecaBundle:Email:Admin\email_ofereca.html.twig',
    						array(
    								
    								'statusUsuario' => $ofereca->getStatusUsuario(),
    								'nome' => $ofereca->getPerson()->getName(),
    								'email' => $ofereca->getPerson()->getEmail(),
    								'telefone' => $ofereca->getPerson()->getTelefone(),
    								'celular' => $ofereca->getPerson()->getTelefone(),
    								'cep' => $ofereca->getCep(),
    								'endereco' => $ofereca->getEndereco(),
    								'cidade' => $ofereca->getCidade(),
    								'bairro' => $ofereca->getBairro(),
    								'estado' => $ofereca->getCidade()->getEstado(),
    								'referencia' => $ofereca->getReferencia(),
    								'localizacao' => $ofereca->getLocalizacao(),
    								'area' => $ofereca->getArea(),
    								'valor' => $ofereca->getValor(),
    								'forma_pagamento' => $ofereca->getFormaPagamento(),
    								'conheceu' => $ofereca->getConheceu(),
    								'informativo' => $ofereca->getInformativo(),
    								'observacao' => $ofereca->getObservacao(),
    						)
    				), 'text/html'
    		);
    
    		$this->get('internit.contact.mail.service')->send($message);
    
    		$success = " Mensagem enviada com sucesso. <br/> Em breve estaremos te retornando.";
    	}
    
    	return $this->render('InternitSiteBaseBundle:Default:ofereca-seu-terreno.html.twig', array(
    			'session' => $page,
    			'form' => $form->createView(),
    			'errors' => $errors,
    			'success' => $success,
    	));
    }
    
    public function cidadeAction()
    {
    	$request = $this->get('request');
    	$estado_id = $request->query->get('estado_id');
    	$estados = $this->getDoctrine()->getRepository('InternitOferecaBundle:Estado')->find($estado_id);
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
	
	public function uploadAction()
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$upload = new UploadHelper();
    	$fileInput = $this->getRequest()->files->get('file');
    	 
    	$title = (strrpos($fileInput->getClientOriginalName(), '.') === false) ? $fileInput->getClientOriginalName() : substr($fileInput->getClientOriginalName(), 0,	strrpos($fileInput->getClientOriginalName(), '.'));
    	$title .= "_".date('U');
    
    	$media = new ImageMedia();
    	$media->setTitle($title.'.'.$fileInput->guessExtension());
    	$media->setLabel($title);
    	$media->setSummary($title);
    	 
    	$upload->setFile($fileInput);
    	$upload->setMedia($media);
    
    	$upload->doUpload();
    	
    	$em->persist($media);
    	$em->flush();
    
    	$return = array("jsonrpc" => "2.0", "result" => null, "id" => $media->getId() ,"title" => $title);
    	return new JsonResponse($return);
    }
    

    private function getElementByClassName(&$parentNode, $tagName, $className) {
    	$nodes = array();
    	 
    	$childNodeList = $parentNode->getElementsByTagName($tagName);
    	for ($i = 0; $i < $childNodeList->length; $i++) {
    		$temp = $childNodeList->item($i);
    		if (stripos($temp->getAttribute('class'), $className) !== false) {
    			$nodes[] = trim($temp->textContent);
    		}
    	}
    	 
    	return $nodes;
    }
    
    private function simpleCurl($url, $post = array(), $get = array()){
    	$url = explode('?',$url,2);
    	if(count($url) === 2){
    		$temp_get = array();
    		parse_str($url[1], $temp_get);
    		$get = array_merge($get, $temp_get);
    	}
    	 
    	$ch = curl_init($url[0]."?".http_build_query($get));
    	curl_setopt ($ch, CURLOPT_POST, 1);
    	curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$response = curl_exec ($ch);
    	curl_close($ch);
    	return $response;
    }
    
    public function buscaCepAction()
    {
    	$request = $this->get('request');
    	$cep = $request->get('cep');
    	
    	$html = $this->simpleCurl('http://m.correios.com.br/movel/buscaCepConfirma.do',array(
    			'cepEntrada'=>$cep,
    			'tipoCep'=>'',
    			'cepTemp'=>'',
    			'metodo'=>'buscarCep'
    	));
    	$dom = new \DOMDocument();
    	@$dom->loadHTML($html);
    	
    	$response = $this->getElementByClassName($dom,'span', 'respostadestaque');
		array_pop($response);
		
		$responseLength = count($response);
		
		$separar = explode("/", $response[$responseLength - 1]);
		
		$response[$responseLength - 1] = trim($separar[0]);
		$response[$responseLength] = trim($separar[1]);
		
		$dados = array_combine(
				array('rua','bairro','cidade','estado'), 
				$response
				);
		
		$estado = $this->getDoctrine()->getRepository('InternitOferecaBundle:Estado')->getEstadoByUF($dados['estado']);
		
		$cidade = $this->getDoctrine()->getRepository('InternitOferecaBundle:Cidade')->getCidadeByName($dados['cidade'], $estado['id']);
		
		$dados['cidade'] = $cidade;
		$dados['estado'] = $estado;
		
		$send = new JsonResponse();
		$send->setContent(json_encode($dados));
    	return $send;
    
    }
    
}