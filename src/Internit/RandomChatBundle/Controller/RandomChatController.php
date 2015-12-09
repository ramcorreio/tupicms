<?php

namespace Internit\RandomChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Tupi\AdminBundle\Controller\ReturnVal;
use Internit\RandomChatBundle\Entity\RandomChatTurn;
use Internit\RandomChatBundle\Entity\RandomChatLinks;

class RandomChatController extends Controller
{	
    public function chatAction()
    {
    	$url = array();
    	//array com links dos chats
    	
    	$em = $this->getDoctrine()->getRepository('InternitRandomChatBundle:RandomChatLinks');
    	
    	$count = $em->getCount();
    	
    	if(!$count){
    		return $this->redirect($this->generateUrl('index'));
    	}
    	
    	$chatLinks = $em->getChatLinks();
    	

    	
    	foreach ($chatLinks as $chatLink)
    	{
    		$url[] = $chatLink->getChat();
    	}
    	

    	//soma quantos links existem
		$sum = $count - 1;
		$turnEm = $this->getDoctrine()->getRepository('InternitRandomChatBundle:RandomChatTurn');
		$turnCount = $turnEm->getCount();
		
		if(!$turnCount){
			$randomChat = new RandomChatTurn();
			$randomChat->setTurn(0);
			$turnEm->persist($randomChat);
			$turnEm->flush();
		}
		//pegando a vez
		$turn = $this->getDoctrine()->getRepository('InternitRandomChatBundle:RandomChatTurn')->getChatTurn();			
		//executa função para verifica qual foi o último chat visitado
		$this->restartTurnOrNot($turn, $sum);
		//atribuindo a vez
		$turnIt = $turn->getTurn();
		

		
		return $this->redirect($url[$turnIt]);
    }

   	protected function restartTurnOrNot($turn, $sum)
   	{
   		$em = $this->getDoctrine()->getManager();
   		
   		//exceção caso haja algum erro no objeto $turn
   		if (!$turn) {
   			throw $this->createNotFoundException(
   					'Houve algum erro no RandomTurn.'
   			);
   		}
   		
   		if($turn->getTurn() == $sum)
   		{
   			$turn->setTurn(0);
   			$em->flush();
   		} else {
   			$turn->setTurn($turn->getTurn()+1);
   			$em->flush();
   		}
   	}
}