<?php
namespace Internit\AcompanhamentoBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Internit\ImovelBundle\Entity\Imovel;
use Internit\AcompanhamentoBundle\Entity\Acompanhamento;
use Internit\AcompanhamentoBundle\Entity\Bloco;

class CreateAcompanhamentoEventListener
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof Imovel) {
            $em = $args->getEntityManager();
            
            $bloco = new Bloco();
            $bloco->setBloco("Único");
            
            $acompanhamento = new Acompanhamento();
            $acompanhamento->setStatus(false);
            $acompanhamento->setImovel($entity);
            $acompanhamento->addBloco($bloco);
            
            $em->persist($acompanhamento);
            $em->flush();
        }
    } 
    
    public function preRemove(LifecycleEventArgs $args)
    {
    	$entity = $args->getEntity();
    	
    	if ($entity instanceof Imovel) {   		
    		$em = $args->getEntityManager();
    		$reposity = $em->getRepository("InternitAcompanhamentoBundle:Acompanhamento");
    		$acompanhamento = $reposity->findByImovelUrl($entity->getUrl());
    		$acompanhamento->getImovel()->setTags(null);
    		$acompanhamento->getImovel()->setMakers(null);
    		$acompanhamento->getImovel()->setStatus(null);
    		
    		$reposity->remove($acompanhamento);
    		$em->flush();
    	}
    }
}